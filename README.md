open https://console.aws.amazon.com/lambda/

click "CloudShell" icon or open https://console.aws.amazon.com/cloudshell/

$ sudo amazon-linux-extras install epel -y

$ sudo yum install https://rpms.remirepo.net/enterprise/remi-release-7.rpm -y

$ yum list available php\* | grep cli

$ sudo yum install php83-php-cli.x86_64

$ curl -sS https://getcomposer.org/installer -o composer-setup.php

$ php83 composer-setup.php --install-dir=/usr/local/bin --filename=composer

$ php83 /usr/local/bin/composer require bref/bref

$ nano serverless.yaml

serverless.yaml content: https://bref.sh/docs/runtimes/fpm-runtime#usage

$ sudo npm install serverless -g

$ serverless config credentials --provider aws --key "key" --secret "secret"

create user(need no "AdministratorAccess", only "AWSCloudFormationFullAccess") and get key secret, ref: https://bref.sh/docs/setup/aws-keys

$ serverless deploy

---
layout: home
---

[![Running PHP made simple. Bref provides tools and documentation to easily deploy and run serverless PHP applications. Learn more](docs/readme-screenshot.jpg)](https://bref.sh/)

[![Build Status](https://travis-ci.com/brefphp/bref.svg?branch=master)](https://travis-ci.com/brefphp/bref)
[![Latest Version](https://img.shields.io/github/release/brefphp/bref.svg?style=flat-square)](https://packagist.org/packages/bref/bref)
[![Monthly Downloads](https://img.shields.io/packagist/dm/bref/bref.svg)](https://packagist.org/packages/bref/bref/stats)

Read more on [the website](https://bref.sh/).
