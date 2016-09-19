echo "--- Starting provisioning ---"
date
#cd /var/www/DE/login2/niam
#php NiamUserManager.php
#php NiamAcknowledge.php
#cd -
result=$(php NiamUserManagerClient.php)
echo "---Completed"
