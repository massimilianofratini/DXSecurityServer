#adapter.sh "\\\"Codice Operazione\\\"\\\"Numero Operazione\\\"\\\"Matricola\\\"\\\"Codice Sistema\\\"\\\"User ID\\\"\\\"Codice Profilo\\\"\\\"Cognome\\\"\\\"Nome\\\"\\\"Datacenter\\\""
PARAMS=$1
DE_PATH=/var/www/DE/login2/niam
#echo "\nLaunching php"
result=$(php NiamAdapterClient.php "$PARAMS")
rescode=$?
echo "*********"
echo "result:" 
echo $result
echo "-------"
echo "rescode:" 
echo $rescode
exit $rescode

