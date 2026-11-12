Put the TiDB Cloud CA certificate in this folder and name it:  ca.pem

How to get it: in TiDB Cloud, open your cluster, click "Connect", and download the CA certificate
(choose the option for connecting with a MySQL client / general). Rename the downloaded file to ca.pem.
This file is a public certificate, not a secret, so it is safe to upload to GitHub.
