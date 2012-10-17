#!/usr/bin/python

import argparse
import urllib
import httplib

parser = argparse.ArgumentParser()

parser.add_argument('-g', '--contact-group', metavar='cgroup', help='name of the solvers contact group')
parser.add_argument('-n', '--name', metavar='name', required=True, help='name of the host or service')
parser.add_argument('--severity', '-i', metavar='severity', default=2, help='severity 0->5 (min->max)')
parser.add_argument('--message', '-m', metavar='message', required=True,
	help='basic error message - this should be static! put dynamic information into additional message field')
parser.add_argument('-t', help='time in unix timestamp format')
parser.add_argument('-c', help='related CI from the CMDB')
parser.add_argument('-x', help='source of this information (e.g. "zabbix"')
parser.add_argument('-s', '--status', metavar='status', required=True,
	help='status (0-OK, 1-PROBLEM, 2-EVENT without pair (logfile event). See documentation on how to use this with different monitoring tools.')
parser.add_argument('--notes', '-a', metavar='notes', help='additional message')
parser.add_argument('--server', '-r', metavar='server', required=True,
	help='IP or host of the Paraguas server')

args = parser.parse_args()

if args.status == 'OK' or args.status == '0' or args.status == 'ok' or args.status == 'UP' or args.status == 'up':
	status = 0
elif args.status == '1' or args.status == 'unknown' or args.status == 'UNKNOWN' or args.status == 'UNREACHABLE' or args.status == 'unreachable':
	status = 1
elif args.status == '2' or args.status == 'log' or args.status == 'LOG':
	status = 2
else:
	status = 1

cgroup = getattr(args, "cgroup", "cont")

params = urllib.urlencode({
	'n': args.name,
	'i': args.severity,
	'm': args.message,
	's': status,
	'a': args.notes,
	'g': cgroup
	})

headers = {"Content-type": "application/x-www-form-urlencoded", "Accept": "text/plain"}

post = httplib.HTTPConnection(args.server, strict=True, port=80);
post.request("POST", "/paraguas/post/", params, headers)
ret = post.getresponse()
print ret.read()
