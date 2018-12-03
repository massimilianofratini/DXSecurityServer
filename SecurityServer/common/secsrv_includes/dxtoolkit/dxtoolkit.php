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

class DXT
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
        if ($action == "C" || $action == "U") {
            //Create or update users
            file_put_contents($fusers, "$action,$username,$name,$surname,$email,,,,NATIVE,,$password,$jetstream\n", FILE_APPEND);
            $cmd = $this->prepareCmd("dx_ctl_users", "dxtools.conf.$applianceId", "-action import -file $fusers");
            $response = shell_exec($cmd);
            //Call change password just in case it changed
            $cmd = $this->prepareCmd("dx_ctl_users", "dxtools.conf.$applianceId", "-action password -username $username -password $password");
            $response = shell_exec($cmd);

        } else if ($action == "D") {
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
         if ($clusterName != "")
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
         $addOpts="-safe ";

         $cmd = $this->prepareCmd("dx_ctl_replication", "dxtools.conf.$applianceId", $addOpts);
         $response = shell_exec($cmd);

         return $response;
     }






//End class
}


?>
