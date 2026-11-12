<?php

/*
 * One-time helper for the Digital Clearance System.
 *
 * Finds and removes duplicate records in the ONLINE database (.env.tidb):
 *   - students that appear twice with the same student number, and
 *   - clearance entries that appear twice for the same student, office, and school year.
 *
 * Nothing is overwritten. When two copies exist, the one with the MOST progress is kept
 * (cleared is kept over rejected, and rejected over pending; if they are equal, the oldest is kept).
 * Only the extra copies are deleted.
 *
 *   php remove-duplicates.php           shows what it WOULD do, and changes nothing
 *   php remove-duplicates.php --apply   does it
 */

$apply = in_array('--apply', $argv, true);
$envFile = __DIR__ . '/.env.tidb';

if (! is_file($envFile)) {
    exit("Could not find .env.tidb. Run this from the project folder.\n");
}

$env = [];

foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
    $line = trim($line);

    if ($line === '' || $line[0] === '#' || ! str_contains($line, '=')) {
        continue;
    }

    [$key, $value] = explode('=', $line, 2);
    $env[trim($key)] = trim(trim($value), "\"'");
}

try {
    if (($env['DB_CONNECTION'] ?? 'mysql') === 'sqlite') {
        $pdo = new PDO('sqlite:' . $env['DB_DATABASE']);
    } else {
        $options = [];

        if (! empty($env['MYSQL_ATTR_SSL_CA'])) {
            $options[PDO::MYSQL_ATTR_SSL_CA] = $env['MYSQL_ATTR_SSL_CA'];
        }

        $pdo = new PDO(
            sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $env['DB_HOST'] ?? '', $env['DB_PORT'] ?? '3306', $env['DB_DATABASE'] ?? ''),
            $env['DB_USERNAME'] ?? '',
            $env['DB_PASSWORD'] ?? '',
            $options
        );
    }
} catch (Throwable $e) {
    exit('Could not connect to the online database: ' . $e->getMessage() . "\n");
}

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

echo $apply ? "MODE: cleaning up (--apply)\n\n" : "MODE: report only. Nothing will be changed.\n\n";

$rank = ['cleared' => 3, 'rejected' => 2, 'pending' => 1];

$totalStudents = (int) $pdo->query('SELECT COUNT(*) FROM students')->fetchColumn();
$totalItems = (int) $pdo->query('SELECT COUNT(*) FROM clearance_items')->fetchColumn();
echo "Before: $totalStudents students, $totalItems clearance entries.\n\n";

$pdo->beginTransaction();

try {
    // 1) the same student number twice: keep the oldest record, move the others' entries to it, delete the extra students
    $studentsRemoved = 0;
    $affected = [];

    $groups = $pdo->query('SELECT student_number FROM students GROUP BY student_number HAVING COUNT(*) > 1')->fetchAll();

    foreach ($groups as $group) {
        $ids = $pdo->prepare('SELECT id FROM students WHERE student_number = ? ORDER BY id');
        $ids->execute([$group['student_number']]);
        $ids = array_column($ids->fetchAll(), 'id');
        $keep = array_shift($ids);
        $affected[] = $group['student_number'];

        foreach ($ids as $extra) {
            $pdo->prepare('UPDATE clearance_items SET student_id = ? WHERE student_id = ?')->execute([$keep, $extra]);
            $pdo->prepare('DELETE FROM students WHERE id = ?')->execute([$extra]);
            $studentsRemoved++;
        }
    }

    // 2) the same entry twice for one student, office, and school year: keep the one with the most progress
    $itemsRemoved = 0;

    $groups = $pdo->query('SELECT student_id, department_id, school_year_id FROM clearance_items GROUP BY student_id, department_id, school_year_id HAVING COUNT(*) > 1')->fetchAll();

    foreach ($groups as $group) {
        $rows = $pdo->prepare('SELECT id, status FROM clearance_items WHERE student_id = ? AND department_id = ? AND school_year_id = ? ORDER BY id');
        $rows->execute([$group['student_id'], $group['department_id'], $group['school_year_id']]);
        $rows = $rows->fetchAll();

        usort($rows, function ($a, $b) use ($rank) {
            return [$rank[$b['status']] ?? 0, $a['id']] <=> [$rank[$a['status']] ?? 0, $b['id']];
        });

        array_shift($rows);

        foreach ($rows as $extra) {
            $pdo->prepare('DELETE FROM clearance_items WHERE id = ?')->execute([$extra['id']]);
            $itemsRemoved++;
        }
    }

    $afterStudents = (int) $pdo->query('SELECT COUNT(*) FROM students')->fetchColumn();
    $afterItems = (int) $pdo->query('SELECT COUNT(*) FROM clearance_items')->fetchColumn();
} catch (Throwable $e) {
    $pdo->rollBack();
    exit('Something went wrong, and nothing was changed: ' . $e->getMessage() . "\n");
}

echo "Duplicate students found:          $studentsRemoved extra record(s)";
echo $affected ? ' (student numbers: ' . implode(', ', array_slice($affected, 0, 10)) . (count($affected) > 10 ? ', ...' : '') . ')' : '';
echo "\nDuplicate clearance entries found: $itemsRemoved extra record(s)\n\n";
echo "After:  $afterStudents students, $afterItems clearance entries.\n\n";

if ($apply) {
    $pdo->commit();
    echo "Done. The extra copies were removed. Nothing else was changed.\n";
} else {
    $pdo->rollBack();
    echo "Nothing was changed. If the numbers above look right, run:  php remove-duplicates.php --apply\n";
}
