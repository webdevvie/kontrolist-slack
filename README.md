Kontrolisto-Slack
======================

A simple tool that takes text data from stdin and sends it to the slack channel of your choosing provided it is configured. Check out slack.yml.dist for an example of how to configure it.

Written by John Bakker

https://github.com/webdevvie/kontrolisto-slack

You can send your message through stdin using a pipe.

Example:
`echo "Hello world" | ./slack.phar send default`

Your config file should be in the same directory as the phar file and named:
slack.yml
or if you changed the phar file's name :
yourfilename.yml

The tool is used by Kontrolisto to send updates to Slack channels.
But can quite easily be used in/for other tools.

Use webdevvie/pakket to build a phar out of this and use package.sh to package it.