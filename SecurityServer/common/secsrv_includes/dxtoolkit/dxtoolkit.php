<?php
/**
 * Created by PhpStorm.
 * User: delphix_admin
 * Date: 29/11/2018
 * Time: 19:32
 *
 * Class implementing a PHP wrapper to the DXToolkit
 *
 * DXTOOLKIT must be installed under Cfg::DXSECSERVER_DIR
 *    Each Engine has its own config file in Cfg::DXSECSERVER_DIR/config directory
 *    Each Engine has its own template files in Cfg::DXSECSERVER_DIR/template directory
 *
 * Available commands:
dx_config
dx_create_env --> IMPLEMENTED
dx_ctl_analytics
dx_ctl_bookmarks
dx_ctl_bundle
dx_ctl_db
dx_ctl_dbhooks
dx_ctl_dsource
dx_ctl_env
dx_ctl_jobs
dx_ctl_js_bookmarks
dx_ctl_js_branch
dx_ctl_js_container
dx_ctl_js_template
dx_ctl_maskingjob
dx_ctl_network_tests
dx_ctl_op_template
dx_ctl_policy
dx_ctl_replication --> IMPLEMENTED
dx_ctl_snapshots
dx_ctl_template
dx_ctl_users --> IMPLEMENTED
dx_encrypt
dx_get_analytics
dx_get_appliance --> IMPLEMENTED
dx_get_audit --> IMPLEMENTED
dx_get_autostart
dx_get_bookmarks
dx_get_capacity --> IMPLEMENTED
dx_get_capacity_history
dx_get_config
dx_get_cpu
dx_get_db_env --> IMPLEMENTED
dx_get_dbhooks
dx_get_disk_latency
dx_get_disk_throughput
dx_get_dsourcesize
dx_get_env --> IMPLEMENTED
dx_get_event
dx_get_faults --> IMPLEMENTED
dx_get_hierarchy
dx_get_instance
dx_get_iscsi_latency
dx_get_iscsi_throughput
dx_get_jobs --> IMPLEMENTED
dx_get_js_bookmarks
dx_get_js_branches
dx_get_js_containers
dx_get_js_datasources
dx_get_js_templates
dx_get_maskingjob
dx_get_network_tests
dx_get_nfs_latency
dx_get_nfs_throughput
dx_get_op_template --> IMPLEMENTED
dx_get_policy
dx_get_replication --> IMPLEMENTED
dx_get_snapshots
dx_get_source_info --> IMPLEMENTED
dx_get_sshkey
dx_get_storage_tests
dx_get_template --> IMPLEMENTED
dx_get_users --> IMPLEMENTED
dx_get_vdbthroughput
dx_logout
dx_provision_vdb --> IMPLEMENTED
dx_refresh_db
dx_remove_db
dx_remove_env --> IMPLEMENTED
dx_resolve_faults
dx_rewind_db
dx_set_autostart
dx_set_dbpass
dx_set_envpass
dx_set_key
dx_snapshot_db
dx_syslog
dx_top
dx_upgrade_db
dxusers.csv.example
dx_v2p

 */

/*
 * SAMPLE IMPLEMENTED COMMANDS
./dx_get_capacity -configfile dxtools.conf.agile -type vdb|source -all -format json > dx_get_capacity.json

./dx_get_db_env -configfile dxtools.conf.agile -all -format json > dx_get_db_env.json

./dx_get_env -configfile dxtools.conf.agile -all -format json > dx_get_env.json

./dx_get_faults -configfile dxtools.conf.agile -all -format json > dx_get_faults.json

./dx_get_instance -configfile dxtools.conf.agile -all -format json > dx_get_instance.json

./dx_get_instance -configfile dxtools.conf.agile -all

    ./dx_get_jobs -configfile dxtools.conf.agile -all -format json > dx_get_jobs.json

./dx_get_op_template -configfile dxtools.conf.agile -all -format json > dx_get_op_template.json

./dx_get_users -configfile dxtools.conf.agile -all -format json > dx_get_users.json

mkdir templates
mkdir templates/agile
    ./dx_get_template -configfile dxtools.conf.agile -all -export -outdir ./templates/agile -format json > dx_get_template.json

./dx_get_source_info -configfile dxtools.conf.agile -all -format json > dx_get_source_info.json

./dx_get_replication -configfile dxtools.conf.agile -all -format json > dx_get_replication.json

*/


require_once(__DIR__ . '/config.php');
require_once_common('conn.php');
require_once_common('mylogger.php');

class DXToolkit
{
    private $dxtPath = Cfg::DXSECSERVER_DIR . "/toolkit";
    private $dxtConfigs = Cfg::DXSECSERVER_DIR . "/toolkit/config";
    private $dxtTemplates = Cfg::DXSECSERVER_DIR . "/toolkit/template";
    private $dxtTmp = Cfg::DXSECSERVER_DIR . "/toolkit/tmp";

    private function prepareCmd($dxtCmd, $cfgFile, $addOpts = "")
    {
        $pCmd = "$this->dxtPath/_CMDNAME_ -configfile $this->dxtConfigs/_CFGFILE_ -all _ADDLOPTS_ ";
        $pCmd = str_replace("_CMDNAME_", $dxtCmd, $pCmd);
        $pCmd = str_replace("_CFGFILE_", $cfgFile, $pCmd);
        $pCmd = str_replace("_ADDLOPTS_", $addlOpts, $pCmd);

        return $pCmd;
    }

    public function getAppliance($applianceId)
    {
        mylogger("Getting appliance info for: $applianceId");
        $cmd = $this->prepareCmd("dx_get_appliance", "dxtools.conf.$applianceId", "-format json");
        $response = shell_exec($cmd);

        return $response;
    }


    public function getAudit($applianceId)
    {
        mylogger("Getting audit info for: $applianceId");
        $cmd = $this->prepareCmd("dx_get_audit", "dxtools.conf.$applianceId", "-format json");
        $response = shell_exec($cmd);

        return $response;
    }

    public function getCapacity($applianceId)
    {
        mylogger("Getting capacity info for: $applianceId");
        $cmd = $this->prepareCmd("dx_get_capacity", "dxtools.conf.$applianceId", "-format json");
        $response = shell_exec($cmd);

        return $response;
    }

    public function getDBEnv($applianceId)
    {
        mylogger("Getting DB Env info for: $applianceId");
        $cmd = $this->prepareCmd("dx_get_db_env", "dxtools.conf.$applianceId", "-format json");
        $response = shell_exec($cmd);

        return $response;
    }

    public function getEnv($applianceId)
    {
        mylogger("Getting Environments info for: $applianceId");
        $cmd = $this->prepareCmd("dx_get_env", "dxtools.conf.$applianceId", "-format json");
        $response = shell_exec($cmd);

        return $response;
    }

    public function getFaults($applianceId)
    {
        mylogger("Getting Faults info for: $applianceId");
        $cmd = $this->prepareCmd("dx_get_faults", "dxtools.conf.$applianceId", "-format json");
        $response = shell_exec($cmd);

        return $response;
    }

    public function getJobs($applianceId)
    {
        mylogger("Getting Jobs info for: $applianceId");
        $cmd = $this->prepareCmd("dx_get_jobs", "dxtools.conf.$applianceId", "-format json");
        $response = shell_exec($cmd);

        return $response;
    }

    public function getJobs($applianceId)
    {
        mylogger("Getting Jobs info for: $applianceId");
        $cmd = $this->prepareCmd("dx_get_jobs", "dxtools.conf.$applianceId", "-format json");
        $response = shell_exec($cmd);

        return $response;
    }

    public function getOpTemplate($applianceId)
    {
        mylogger("Getting Operations Templates info for: $applianceId");
        $cmd = $this->prepareCmd("dx_get_op_template", "dxtools.conf.$applianceId", "-format json");
        $response = shell_exec($cmd);

        return $response;
    }

    public function getUsers($applianceId)
    {
        mylogger("Getting Users info for: $applianceId");
        $cmd = $this->prepareCmd("dx_get_users", "dxtools.conf.$applianceId", "-format json");
        $response = shell_exec($cmd);

        return $response;
    }

    public function getTemplate($applianceId)
    {
        mylogger("Getting Templates info for: $applianceId");
        if (!file_exists($this->dxtTemplates)) {
            mkdir($this->dxtTemplates, 0775, true);
        }
        $cmd = $this->prepareCmd("dx_get_template", "dxtools.conf.$applianceId", "-export -outdir $this->dxtTemplates/template.$applianceId -format json");
        $response = shell_exec($cmd);

        return $response;
    }

    public function getReplication($applianceId)
    {
        mylogger("Getting Replication info for: $applianceId");
        $cmd = $this->prepareCmd("dx_get_replication", "dxtools.conf.$applianceId", "-format json");
        $response = shell_exec($cmd);

        return $response;
    }

    public function ctlUsers($applianceId, $action, $username, $password = "", $name = "autocreated", $surname = "autocreated", $email = "autocreated@delphix.local", $profile = "", $jetstream = "N")
    {
        mylogger("Managing users on engine with the following info: $applianceId, $action, $username, ***, $profile");
        //Create users file
        if (!file_exists($this->dxtTmp)) {
            mkdir($this->dxtTmp, 0775, true);
        }
        $fusers = tempnam($this->dxtTmp, "$applianceId-users.");
        file_put_contents($fusers, "# operation,username,first_name,last_name,email address,work_phone,home_phone,cell_phone,type(NATIVE|LDAP),principal_credential,password,admin_priv,js_user\n", FILE_APPEND);
        if ($action == "C" || $action == "U" || $action == "c" || $action == "u") {
            //Create or update users
            file_put_contents($fusers, "$action,$username,$name,$surname,$email,,,,NATIVE,,$password,$jetstream\n", FILE_APPEND);
            $cmd = $this->prepareCmd("dx_ctl_users", "dxtools.conf.$applianceId", "-action import -file $fusers");
            $response = shell_exec($cmd);
            //Call change password just in case it changed
            $cmd = $this->prepareCmd("dx_ctl_users", "dxtools.conf.$applianceId", "-action password -username $username -password $password");
            $response = shell_exec($cmd);

        } else if ($action == "D" || $action == "d") {
            file_put_contents($fusers, "$action,$username,,,,,,,,,,\n", FILE_APPEND);
            $cmd = $this->prepareCmd("dx_ctl_users", "dxtools.conf.$applianceId", "-action import -file $fusers");
            $response = shell_exec($cmd);
        }

        return $response;
    }

/*
Options -envname, -envtype, -host, -toolkitdir, -username and -authtype are required.
Usage:
dx_create_env [ -engine|d <delphix identifier> | -all ] [ -configfile file ] -envname environmentname -envtype unix | windows -host hostname
-toolkitdir toolkit_directory | -proxy proxy
-username user_name -authtype password | systemkey [ -password password ]
[-clustername name]
[-clusterloc loc]
[-sshport port]
[-asedbuser user]
[-asedbpass password]
[ -version ] [ -help ] [ -debug ]

Arguments:
Delphix Engine selection - if not specified a default host(s) from
dxtools.conf will be used.

-engine|d Specify Delphix Engine name from dxtools.conf file
-all Display databases on all Delphix appliance
-configfile file Location of the configuration file. A config file
search order is as follow: - configfile parameter - DXTOOLKIT_CONF
variable - dxtools.conf from dxtoolkit location
-envname environmentname Environment name
-envtype type Environment type - windows or unix
-host hostname Host name / IP of server being added to Delphix Engine
-toolkitdir toolkit_directory Location for toolkit directory for
Unix/Linux or location of Delphix Connector directory for Windows
-proxy proxy Proxy server used to access dSource
-username user_name Server user name
-authtype password | systemkey Authorization type - password or SSH key
-password password If password is specified as authtype - a user
password has to be specified
-clustername name Cluser name (CRS name for RAC)
-clusterloc loc Cluser location (CRS home for RAC)
-sshport port SSH port
-asedbuser user ASE DB user for source detection
-asedbpass password ASE DB password for source detection
*/
     public function ctlCreateEnv ($applianceId, $envName, $envType, $hostname, $toolkitDir, $username, $password, $clusterName="", $clusterLoc="") {
         mylogger("Creating environment with info: $applianceId, $envName, $envType, $hostname, $toolkitDir, $username");
         $addOpts="-envname $envName -envtype $envType -host $hostname -toolkitdir $toolkitDir ";
         $addOpts.=" -username $username -authtype password -password $password ";
         if ($clusterName !== "")
            $addOpts.="-clustername $clusterName -clusterloc $clusterLoc";

         $cmd = $this->prepareCmd("dx_create_env", "dxtools.conf.$applianceId", $addOpts);
         $response = shell_exec($cmd);

         return $response;
     }


/*
SYNOPSIS
dx_ctl_replication [-engine|d <delphix identifier> | -all ]
-profilename profile
[-safe]
[-nowait]
[-help|?]
[-debug ]

DESCRIPTION
Start an replication using a profile name

ARGUMENTS
Delphix Engine selection - if not specified a default host(s) from
dxtools.conf will be used.

-engine|d Specify Delphix Engine name from dxtools.conf file
-all Display databases on all Delphix appliance
-configfile file Location of the configuration file. A config file
search order is as follow: - configfile parameter - DXTOOLKIT_CONF
variable - dxtools.conf from dxtoolkit location
-profilename profile Specify a profile name to run

OPTIONS
-nowait Don't wait for a replication job to complete. Job will be
    running in backgroud.
    -safe Enable "safe" replication. If there was a VDB/dSource deletion
    operation on primary engine, replication job won't be started
-help Print this screen
-debug Turn on debugging
*/
     public function ctlReplication ($applianceId, $repName) {
         mylogger("Starting Replication with info: $applianceId, $repName");
         $addOpts="-profilename $repName -safe ";

         $cmd = $this->prepareCmd("dx_ctl_replication", "dxtools.conf.$applianceId", $addOpts);
         $response = shell_exec($cmd);

         return $response;
     }


/*
SYNOPSIS
dx_provision_vdb [ -engine|d <delphix identifier> | -all ] [ -configfile file ]
-group group_name
-sourcename src_name
-targetname targ_name
-dbname db_name | -path vfiles_mountpoint
-environment environment_name
-type oracle|mssql|sybase|vFiles
-envinst OracleHome/MSSQLinstance/SybaseServer
[-creategroup]
[-srcgroup Source group]
[-timestamp LATEST_SNAPSHOT|LATEST_POINT|time_stamp]
[-template template_name]
[-mapfile mapping_file]
[-instname SID]
[-uniqname db_unique_name]
[-mntpoint mount_point ]
[-cdb container_name]
[-noopen]
[-truncateLogOnCheckpoint]
[-archivelog yes/no]
[-configureclone pathtoscript | operation_template_name ]
[-prerefresh  pathtoscript | operation_template_name ]
[-postrefresh pathtoscript | operation_template_name ]
[-prerewind pathtoscript | operation_template_name ]
[-postrewind pathtoscript | operation_template_name ]
[-presnapshot pathtoscript | operation_template_name ]
[-postsnapshot pathtoscript | operation_template_name ]
[-prestart pathtoscript | operation_template_name ]
[-poststart pathtoscript | operation_template_name ]
[-prestop pathtoscript | operation_template_name ]
[-poststop pathtoscript | operation_template_name ]
[-prescript pathtoscript ]
[-postscript pathtoscript ]
[-recoveryModel model ]
[-snapshotpolicy name]
[-retentionpolicy name]
[-additionalMount envname,mountpoint,sharedpath]
[-rac_instance env_node,instance_name,instance_no ]
[-redoGroup N]
[-redoSize N]
[-listeners listener_name]
[-hooks path_to_hooks]
[-envUser username]
[-maskingjob jobname]
[-autostart yes]
[-vcdbname name]
[-vcdbgroup groupname]
[-vcdbdbname vcdb_name]
[-vcdbuniqname vcdb_unique_name]
[-vcdbinstname vcdb_instance_name]
[-vcdbtemplate vcdb_template_name]
[-help] [-debug]

DESCRIPTION
Provision VDB from a defined source on the defined target environment.

ARGUMENTS
Delphix Engine selection - if not specified a default host(s) from dxtools.conf will be used.
-engine|d Specify Delphix Engine name from dxtools.conf file
-all Display databases on all Delphix appliance
-configfile file Location of the configuration file. A config file
search order is as follow: - configfile parameter - DXTOOLKIT_CONF
variable - dxtools.conf from dxtoolkit location

VDB arguments
-type type Type (oracle|mssql|sybase|vFiles)
-group name Group Name
-creategroup Specify this option to create a new group on Delphix Engine while proviioning a new VDB
-sourcename name dSource Name
-targetname name Target name
-dbname name Target database name
-path path Mount point location for vFiles
-srcgroup Source group Group name where source is located
-timestamp timestamp Time stamp formats:
YYYY-MM-DD HH24:MI:SS or LATEST_POINT for point in time,

@YYYY-MM-DDTHH24:MI:SS.ZZZ , YYYY-MM-DD HH24:MI or LATEST_SNAPSHOT for
snapshots. @YYYY-MM-DDTHH24:MI:SS.ZZZ is a snapshot name from
dx_get_snapshot, while YYYY-MM-DD HH24:MI is a snapshot time in GUI
format

Default is LATEST_SNAPSHOT

-location location Point in time defined by SCN for Oracle and LSN for
MS SQL
-environment environment_name Target environment name
-envinst environment_instance Target environment Oracle Home, MS SQL server instance, Sybase server name, etc
-template template_name Target VDB template name (for Oracle)
-mapfile filename Target VDB mapping file (for Oracle)
-instname instance_name Target VDB instance name (for Oracle)
-uniqname db_unique_name Target VDB db_unique_name (for Oracle)
-mntpoint path Set a mount point for VDB (for Oracle and Sybase)
-cdb container_name Set a target container database for vPDB
-vcdbname name Set a virtual CDB name for vPDB
-vcdbdbname name Set a virtual CDB database name for vPDB
-vcdbuniqname vcdb_unique_name Set a virtual CDB unique database name
for vPDB If not set, it will be equal to vcdbdbname
-vcdbinstname vcdb_instance_name Set a virtual CDB instance name for
vPDB If not set, it will be equal to vcdbdbname
-vcdbgroup groupname Set a virtual CDB groupname
-vcdbtemplate vcdb_template_name Set a virtual CDB template
-noopen Don't open database after provision (for Oracle)
-archivelog yes/no Create VDB in archivelog (yes - default) or noarchielog (no) (for Oracle)
-truncateLogOnCheckpoint Truncate a log on checkpoint. Set this parameter to enable truncate operation (for Sybase)
-snapshotpolicy policy_name Snapshot policy name for VDB
-maskingjob jobname Name of masking job to use during VDB provisioning
-retentionpolicy retention_policy Retention policy name for VDB
-recoveryModel model Set a recovery model for MS SQL database. Allowed values BULK_LOGGED,FULL,SIMPLE
-additionalMount envname,mountpoint,sharedpath Set an additinal mount point for vFiles - using a syntax
    environment_name,mount_point,sharedpath
     ex. -additionalMount target1,/u01/app/add,/

-rac_instance env_node,instance_name,instance_no Comma separated
    information about node name, instance name and instance number for a RAC
    provisioning Repeat option if you want to provide information for more
    nodes
     ex. -rac_instance node1,VBD1,1 -rac_instance node2,VBD2,2

-redoGroup N Create N redo groups
-redoSize N Each group will be N MB in size
-listeners listener_name Use listener named listener_name
-hooks path_to_hooks Import hooks exported using dx_get_hooks
-envUser username Use an environment user "username" for provisioning database
-autostart yes Set VDB autostart flag to yes. Default is no

OPTIONS
    -help Print usage information
    -debug Turn on debugging

EXAMPLES
    Provision an Oracle VDB using latest snapshot
     dx_provision_vdb -d Landshark -sourcename "Employee Oracle DB" -dbname autoprov -targetname autoprov -group Analytics
                      -environment LINUXTARGET -type oracle -envinst "/u01/app/oracle/product/11.2.0/dbhome_1"

    Provision an Oracle vPDB using a virtual vCDB
     dx_provision_vdb -d Landshark -type oracle -group "test" -creategroup -sourcename "PDBX1"  -srcgroup "Sources" -targetname "vPDBtest"  -dbname "vPDBtest" -environment "marcintgt"  -envinst "/u01/app/ora12102/product/12.1.0/dbhome_1"  -envUser "ora12102"  \
     -vcdbtemplate slon -vcdbname ala -vcdbdbname slon -vcdbgroup test -mntpoint "/mnt/provision"

    Provision a Sybase VDB using a latest snapshot
     dx_provision_vdb -d Landshark -group Analytics -sourcename 'ASE pubs3 DB' -targetname testsybase -dbname testsybase -environment LINUXTARGET -type sybase -envinst LINUXTARGET

    Provision a Sybase VDB using a snapshot name "@2015-09-08T08:46:47.000"
    (to list snapshots use dx_get_snapshots)
     dx_provision_vdb -d Landshark -group Analytics -sourcename 'ASE pubs3 DB' -targetname testsybase -dbname testsybase -environment LINUXTARGET -type sybase -envinst LINUXTARGET -timestamp "@2015-09-08T08:46:47.000"

    Provision a vFiles using a latest snapshot
     dx_provision_vdb -d Landshark43 -group Analytics -sourcename "files" -targetname autofs -path /mnt/provision/home/delphix -environment LINUXTARGET -type vFiles

    Provision a empty vFiles
     dx_provision_vdb -d Landshark5 -type vFiles -group "Test" -creategroup -empty -targetname "vFiles" -dbname "/home/delphix/de_mount" -environment "LINUXTARGET" -envinst "Unstructured Files"  -envUser "delphix"

    Provision a MS SQL using a latest snapshot
     dx_provision_vdb -d Landshark -group Analytics -sourcename AdventureWorksLT2008R2 -targetname autotest - dbname autotest -environment WINDOWSTARGET -type mssql -envinst MSSQLSERVER

    Provision a MS SQL using a snapshot from "2015-09-23 10:23"
     dx_provision_vdb -d Landshark -group Analytics -sourcename AdventureWorksLT2008R2 -targetname autotest - dbname autotest -environment WINDOWSTARGET -type mssql -envinst MSSQLSERVER -timestamp "2015-09-23 10:23"
*/


    public function ctlProvisionOracleVDB ($applianceId, $sourceName, $targetName,
                                           $group, $env, $envInst, $timeStamp="", $dbName="",$uniqueName="", $template="", $noOpen="",
                                           $archivelog="yes", $redoGroup="", $redoSize="", $listener="", $maskingJob="" ) {

        mylogger("Creating Oracle VDB with info: $applianceId, $sourceName, $dbName, $targetName, $group, $env, $envInst, $timeStamp, $uniqueName, $template, $noOpen,$archivelog, $redoGroup, $redoSize, $listener, $maskingJob");

        $addOpts = "-sourcename $sourceName -targetname $targetName -group $group ";
        $addOpts .= " -environment $env -type oracle -envinst $envInst -archivelog $archivelog ";
        $addOpts .= ($timeStamp !== "" ? " -timestamp $timeStamp" : "");
        $addOpts .= ($dbName !== "" ? " -dbname $dbName" : "");
        $addOpts .= ($uniqueName !== "" ? " -uniqname $uniqueName" : "");
        $addOpts .= ($template !== "" ? " -template $template" : "");
        $addOpts .= ($noOpen !== "" ? " -noopen" : "");
        $addOpts .= ($redoGroup !== "" ? " -redoGroup $redoGroup" : "");
        $addOpts .= ($redoSize !== "" ? " -redoSize $redoSize" : "");
        $addOpts .= ($listener !== "" ? " -listeners $listener" : "");
        $addOpts .= ($maskingJob !== "" ? " -maskingjob $maskingJob" : "");

        $cmd = $this->prepareCmd("dx_create_env", "dxtools.conf.$applianceId", $addOpts);
        $response = shell_exec($cmd);

        return $response;
    }


    public function ctlProvisionVFiles ($applianceId, $sourceName, $targetName,
                                        $group, $env, $envInst="Unstructured Files", $timeStamp="", $dbName="", $maskingJob="" ) {

        mylogger("Creating VFiles with info: $applianceId, $sourceName, $dbName, $targetName, $group, $env, $envInst, $timeStamp, $maskingJob");

        $addOpts = "-sourcename $sourceName -targetname $targetName -group $group ";
        $addOpts .= " -environment $env -type vFiles -envinst \"$envInst\" ";
        $addOpts .= ($timeStamp !== "" ? " -timestamp $timeStamp" : "");
        $addOpts .= ($dbName !== "" ? " -dbname $dbName" : "");
        $addOpts .= ($maskingJob !== "" ? " -maskingjob $maskingJob" : "");

        $cmd = $this->prepareCmd("dx_create_env", "dxtools.conf.$applianceId", $addOpts);
        $response = shell_exec($cmd);

        return $response;
    }

    public function ctlProvisionEmptyVFiles ($applianceId, $targetName, $group, $env, $envInst="Unstructured Files", $dbName="") {

        mylogger("Creating VFiles with info: $applianceId, $sourceName, $dbName, $targetName, $group, $env, $envInst, $timeStamp, $empty, $maskingJob");

        $addOpts = "-empty -targetname $targetName -group $group ";
        $addOpts .= " -environment $env -type vFiles -envinst \"$envInst\" ";
        $addOpts .= ($dbName !== "" ? " -dbname $dbName" : "");
        $cmd = $this->prepareCmd("dx_create_env", "dxtools.conf.$applianceId", $addOpts);
        $response = shell_exec($cmd);

        return $response;
    }



//End class
}


?>
