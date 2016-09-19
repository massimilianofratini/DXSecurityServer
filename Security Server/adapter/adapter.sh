#adapter.sh "\\\"Codice Operazione\\\"\\\"Numero Operazione\\\"\\\"Matricola\\\"\\\"Codice Sistema\\\"\\\"User ID\\\"\\\"Codice Profilo\\\"\\\"Cognome\\\"\\\"Nome\\\"\\\"Datacenter\\\""
PARAMS=$1
#echo "\nLaunching php"
#php NiamAdapter.php "$PARAMS"
result=$(php NiamAdapter.php "$PARAMS")
rescode=$?
echo "*********"
echo "result:" 
echo $result
echo "-------"
echo "rescode:" 
echo $rescode
exit $rescode

