#import urllib.request
#import urllib.parse
import urllib
import http.client

from base64 import b64encode
import os
import sys
import json
import getpass
import time
import csv
from optparse import OptionParser
from datetime import datetime, timedelta
from time import gmtime, strftime, strptime
from collections import defaultdict

# Delphix User Create
# R.A.Ekins
# Version Sept 6th 2013 (v1.0.0)
#
  
# Global Variables

VERSION = '1.0.0'
BANNER = ('=' * 200)
HEADER = 'Delphix User Create (' + VERSION + ')'
VERBOSE_FLAG = False
DEBUG_LEVEL = 0

def user_login(host, port, user, password):
    # Set-up HTTP header
    userAgent = 'Jakarta Commons-HttpClient/3.1'
    cookie = ''
    hdrs= {'Content-Type' : 'application/json', 'User-agent' : userAgent, 'Cookie' : cookie}


    #Establish Session
    data = {'type' : 'APISession',
                                'version' : {
                                'type' : 'APIVersion',
                                'major' : 1,
                                'minor' : 0,
                                'micro' : 0}
                                }
    params = json.dumps(data)
    path = '/resources/json/delphix/session'
    url = 'http://%s:%s%s'%(host,port,path)

    # Perform action
    conn = http.client.HTTPConnection(host, port)
    conn.set_debuglevel(DEBUG_LEVEL)
    conn.request('POST', path, params, headers=hdrs)

    response = conn.getresponse()
    response_data = response.read()
    cookie = response.getheader('set-cookie')

    jsonString = response_data.decode('utf8')
    jsonData = json.loads(jsonString)
                                    
    if (jsonData['status']) != 'OK':
        action = (jsonData['error']['action'])
        message = (jsonData['error']['message'])
        details = (jsonData['error']['details'])
        
        print('Action:', action)
        print('Message:', message)
        print('Details:', details)
        print(BANNER)
        sys.exit('Exiting: Unable to establish session')

    #Establish Login
    hdrs= {'Content-Type' : 'application/json', 'User-agent' : userAgent, 'Cookie' : cookie}
    data = {'type' : 'LoginRequest','username' : user,'password' : password}
    params = json.dumps(data)

    path = '/resources/json/delphix/login'
    url = 'http://%s:%s%s'%(host,port,path)

    # Perform action
    conn = http.client.HTTPConnection(host, port)
    conn.set_debuglevel(DEBUG_LEVEL)
    conn.request('POST', path, params, headers=hdrs)
            
    response = conn.getresponse()
    response_data = response.read()

    jsonString = response_data.decode('utf8')
    jsonData = json.loads(jsonString)

    if (jsonData['status']) != 'OK':
        action = (jsonData['error']['action'])
        message = (jsonData['error']['message'])
        details = (jsonData['error']['details'])
        
        print('Action:', action)
        print('Message:', message)
        print('Details:', details)
        print(BANNER)
        sys.exit('Exiting: Invalid Username or Password')
    return(cookie)

def get_url(host,port,path,params,cookie):
    # Set-up HTTP header
    userAgent = 'Jakarta Commons-HttpClient/3.1'
    
    #Set-up header for HTTP Auth
    #hdrs= {'Authorization' : 'Basic %s' % USERANDPASS,'Content-Type' : 'application/json', 'User-agent' : userAgent }
    hdrs= {'Content-Type' : 'application/json', 'User-agent' : userAgent, 'Cookie' : cookie}
    
    conn = http.client.HTTPConnection(host, port)
    conn.set_debuglevel(DEBUG_LEVEL)
    conn.request('GET', path, headers=hdrs)
    
    response = conn.getresponse()
    response_data = response.read()
    
    if DEBUG_LEVEL != 0:
        print('Response Status:', response.status)
        print('Reason:', response.reason)
        print('HTTP Header:', response.info())
        print(response.getheader('Content-Length'))
        print('Cookie:', cookie)
    jsonData = response_data.decode('utf8')
    return(jsonData)

def post_url(host,port,path,params,cookie):
    # Set-up HTTP header
    userAgent = 'Jakarta Commons-HttpClient/3.1'
    
    hdrs= {'Content-Type' : 'application/json', 'User-agent' : userAgent, 'Cookie' : cookie}
    
    conn = http.client.HTTPConnection(host, port)
    conn.set_debuglevel(DEBUG_LEVEL)
    
    conn.request('POST', path, params, headers=hdrs)
    
    response = conn.getresponse()
    response_data = response.read()
    
    if DEBUG_LEVEL != 0:
        print('Response Status:', response.status)
        print('Reason:', response.reason)
        print('HTTP Header:', response.info())
        print(response.getheader('Content-Length'))
        print('Cookie:', cookie)
    jsonData = response_data.decode('utf8')
    return(jsonData)

def get_System(host,port,cookie):
    # Delphix Users
    data = ''
    params = json.dumps(data)
    
    path = '/resources/json/delphix/system'
    url = 'http://%s:%s%s'%(host,port,path)
    
    # Perform action
    jsonString = get_url(host,port,url,params,cookie)
    jsonData = json.loads(jsonString)
    
    if VERBOSE_FLAG:
        print(BANNER)
        print(json.dumps(jsonData, sort_keys=False, indent=4))
    
    # Count of returned rows
    results = jsonData['result']
    res = len(results)
    
    if res == 0:
        print('No system found')
    else:
        buildTitle =  (jsonData['result']['buildTitle'])
    
    return(buildTitle)

def create_User(host,port,cookie,row):
    # Create Delphix User
    
    action = row[0]
    name = row[1]
    firstName = row[2]
    lastName = row[3]
    emailAddress = row[4]
    authenticationType = row[5]
    principal = row[6]
    credential = row[7]
    workPhoneNumber = row[8]
    delphixAdmin = row[9]
    
    if authenticationType == 'LDAP':
        data = {'type': 'User',
                'name': name,
                'authenticationType': authenticationType,
                'principal': principal,
                'emailAddress': emailAddress,
                'firstName': firstName,
                'lastName': lastName,
                'workPhoneNumber': workPhoneNumber}
    else:
        data = {'type': 'User',
                'name': name,
                'authenticationType': authenticationType,
                'credential': {
                    'type': 'PasswordCredential',
                    'password': credential},
                'emailAddress': emailAddress,
                'firstName': firstName,
                'lastName': lastName,
                'workPhoneNumber': workPhoneNumber}

    params = json.dumps(data)
    path = '/resources/json/delphix/user'
    url = 'http://%s:%s%s'%(host,port,path)
    
    # Perform action
    jsonString = post_url(host,port,url,params,cookie)
    jsonData = json.loads(jsonString)
    
    if VERBOSE_FLAG:
        print(BANNER)
        print(json.dumps(jsonData, sort_keys=False, indent=4))

    if (jsonData['status']) != 'OK':
        print('Skipping, unable to create', name)
        #print(json.dumps(jsonData, sort_keys=False, indent=4))
        exit_code = 1
    else:
        #print('Created Delphix Account:', name)
        userRef = jsonData['result']
        exit_code = 0

        if delphixAdmin == 'Yes':
            #print('Grant Delphix Admin role to', name)
            data = {'type': 'Authorization',
                    'role': 'OWNER',
                    'target': 'DOMAIN',
                    'user': userRef}

            params = json.dumps(data)
            path = '/resources/json/delphix/authorization'
            url = 'http://%s:%s%s'%(host,port,path)

            # Perform action
            jsonString = post_url(host,port,url,params,cookie)
            jsonData = json.loads(jsonString)

            if VERBOSE_FLAG:
                print(BANNER)
                print(json.dumps(jsonData, sort_keys=False, indent=4))

            if (jsonData['status']) != 'OK':
                #print(json.dumps(jsonData, sort_keys=False, indent=4))
                print('Delphix Admin Authorisation failed for', name)
                exit_code = 1

    return(exit_code)

def get_Users(host,port,cookie,name):
    # Delphix Users
    data = ''
    params = json.dumps(data)
    
    if name == None:
        path = '/resources/json/delphix/user'
    else:
        path = '/resources/json/delphix/user/' + name

    url = 'http://%s:%s%s'%(host,port,path)
    
    # Perform action
    jsonString = get_url(host,port,url,params,cookie)
    jsonData = json.loads(jsonString)
    
    if VERBOSE_FLAG:
        print(BANNER)
        print(json.dumps(jsonData, sort_keys=False, indent=4))
    
    # Count of returned rows
    results = jsonData['result']
    res = len(results)
    
    if res == 0:
        print('No accounts found')
    else:
        print('Existing Users')
        print('{0:20} {1:15} {2:15} {3:30} {4:25} {5:80}'.format('Name', 'First Name', 'Last Name', 'Email Address', 'Work Phone', 'Principal'))
        print(BANNER)
        
    x = 0
    while (x <res):
        name =  (jsonData['result'][x]['name'])
        authenticationType = (jsonData['result'][x]['authenticationType'])
        emailAddress = (jsonData['result'][x]['emailAddress'])
        principal = (jsonData['result'][x]['principal'])
        credential = (jsonData['result'][x]['credential'])
        firstName = (jsonData['result'][x]['firstName'])
        lastName = (jsonData['result'][x]['lastName'])
        workPhoneNumber = (jsonData['result'][x]['workPhoneNumber'])
        
        print('{0:20} {1:15} {2:15} {3:30} {4:25} {5:80}'.format(name, firstName, lastName, emailAddress, workPhoneNumber, principal))
        
        x = x +1
    print(BANNER)

    return()


def parsecl():
    usage = 'usage: %prog [options]'
    version = '%prog ' + VERSION
    description = """This application has been developed against the Delphix V3.2 RESTful Web Service interfaces. On first use you are required to select a Delphix VDB (Virtual Database). When you exit the applicaiton settings are saved in a configuration file for future use. Developed and tested using Python 3.2 on Mac OS 10.8. Please contact ron.ekins@delphix.com."""
    
    parser = OptionParser(usage=usage, version=version, description=description)
    
    
    parser.add_option('-d', '--debug',
                      type = 'int',
                      dest = 'DEBUG_LEVEL',
                      default = 0,
                      help = 'Debug level, use to get HTTP headers')
                      
    parser.add_option('-p', '--password',
                      action = 'store',
                      type = 'string',
                      dest = 'password',
                      help = 'Delphix Password')
                                        
    parser.add_option('-P', '--port',
                      type = 'int',
                      dest = 'port',
                      default = 80,
                      help = 'Delphix Server Port [default: %default]')
                                                                            
    parser.add_option('-s', '--server',
                      action = 'store',
                      type = 'string',
                      dest = 'server',
                      help = 'Delphix Server')
                      
    parser.add_option('-u', '--user',
                      action = 'store',
                      type = 'string',
                      dest = 'user',
                      help = 'Delphix User Name')
    
    parser.add_option('-v', '--verbose',
                      action = 'store_true',
                      dest = 'VERBOSE_FLAG',
                      default = False,
                      help = 'Verbose mode, full JSON output generated')

    
    (options, args) = parser.parse_args()
    
    '''
    print("Options:", options)
    print("Args:", args)
    '''
    return(options)


def main():
    # Setup variables
    global VERBOSE_FLAG
    global DEBUG_LEVEL
    exit_code = 0
    created = 0
    
    # Check for command line parameters
    options = parsecl()
    password = options.password
    port = options.port
    host = options.server
    user = options.user
    name = None
    DEBUG_LEVEL = options.DEBUG_LEVEL
    VERBOSE_FLAG = options.VERBOSE_FLAG

    if user == None:
        user = input('Enter Username: ')
        user = user.strip()

    if password == None:
        password = getpass.getpass('Enter your password: ')
        password = password.strip()

    if host == None or port == '':
        sys.exit('Exiting: You must provide Host and Port details')

    '''
    print('Password', password)
    print('User', user)
    print('Port:', port)
    print('Host:', host)
    print('DEBUG LEVEL:', DEBUG_LEVEL)
    print('VERBOSE FLAG:', VERBOSE_FLAG)
    '''
    
    # Attempt logon
    cookie = user_login(host, port, user, password)
    
    buildTitle = get_System(host, port, cookie)

    print(BANNER)
    print(HEADER + ' - ' + host + ':' + str(port) + ' (', buildTitle, ')')
    print(strftime('%Y/%m/%d %H:%M:%S %Z', gmtime()))
    print(BANNER)
    
    # List Users
    get_Users(host, port, cookie, name)
    
    # Read user file
    user_file = 'user_list.csv'
    
    try:
        ifile  = open(user_file, 'r')
    except IOError:
        errMsg = 'Error: can\'t find or read ' + user_file
        sys.exit(errMsg)

    reader = csv.reader(ifile,delimiter=',')

    print('New Users')
    print('{0:20} {1:15} {2:15} {3:30} {4:25} {5:80}'.format('Name', 'First Name', 'Last Name', 'Email Address', 'Work Phone', 'Principal'))
    print(BANNER)

    x = 0
    for row in reader:
        action = row[0]
        name = row[1]
        firstName = row[2]
        lastName = row[3]
        emailAddress = row[4]
        authenticationType = row[5]
        principal = row[6]
        credential = row[7]
        workPhoneNumber = row[8]
        delphixAdmin = row[9]

        if x != 0:
            if action == 'C':
                creation = create_User(host, port, cookie, row)
                if creation == 0:
                    created = created + 1
                    print('{0:20} {1:15} {2:15} {3:30} {4:25} {5:80}'.format(name, firstName, lastName, emailAddress, workPhoneNumber, principal))

        x = x +1

    print(BANNER)
    print('Created', created, 'Delphix Accounts')
    print(time.strftime('%Y/%m/%d %H:%M:%S %Z', gmtime()))
    print(BANNER)
    sys.exit(exit_code)

main()
