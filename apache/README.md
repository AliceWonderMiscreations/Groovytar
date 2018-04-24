Apache Configuration
====================

This is an open-source project meaning you can run your own instance, which you
may want to do if you are either super-paranoid about IP address tracking of
your visitors or if you want to run your own Identicon generators that are not
part of Groovytar.

If you want to customize Groovytar, I recommend forking the master branch into
your own github account where you can make your customizations.

For those who just want to run what is in master, the github repository is set
up to allow you to just clone it onto your server and run it.

1. [Composer Install](#composer-install)
2. [Non Composer Install](#non-composer-install)
3. [Generated File Cache Directory](#generated-file-cache-directory)
4. [Transport Layer Security](#transport-layer-security)
   1. [Custom Lets Encrypt Shell Script](#custom-lets-encrypt-shell-script)
   2. [Certificate Update Strategy](#certificate-update-strategy)
   3. [DANE Rotation](#dane-rotation)
   4. [HPKP Rotation](#hpkp-rotation)
5. [Apache Log Configuration](#apache-log-configuration)


Composer Install
----------------

There are some dependencies that are installable via composer. Just run

    composer install

in the root directory that has the `composer.json` file.

This will also pull in some devel dependencies I use, such as `vimeo/psalm`, to
help keep the quality of the code high. You can use the `--no-dev` flag to save
space.


Non Composer Install
--------------------

You will need to make sure the `lib/' directory is where a PSR-4 autoloader can
find what is needed, and make sure the dependencies are where a PSR-4
autoloader can find them, and modify the `www/LOADER.php` file.


Generated File Cache Directory
------------------------------

When an identicon is generated, the SVG output is cached to file so that future
requests can just grab it from file.

The web server will need to have write access to the `generated` directory for
this to work. Not that this directory is not within the web root, a PHP wrapper
is used to read the files and serve them to the user.


Transport Layer Security
------------------------

I highly recommend you only use TLS to serve the identicons. In fact there
really are very few reasons to ever *not* use TLS.

You can use [Let's Encrypt](https://letsencrypt.org/) to get a free TLS
certificate.

### Custom Lets Encrypt Shell Script

If like me, you do not like an automated bot that makes connections to third
party resource updating your server daemon configuration files - I have this
shell script that can be run manually when you need to first generate a TLS
certificate and then when you need to renew it:

[letsencrypt.sh](https://gist.github.com/AliceWonderMiscreations/de1a37b41df545eba3b6d6e77f6f29fb)

That script will generate a private key and CSR and then request a signed
certificate.

It uses the `certbot` in `standalone` mode which means you need to stop your
web server before running it.

It only generates a new private key when the existing private key for the
primary domain either does not exist or is over 320 days old. Best practices
say to generate a new private key once a year, which is why I do that.

Assuming certbot can validate that you own the domain name (which it does by
opening port 80/443 and responding to some automated challenges) you will then
get a signed cert.

The script also generates TLSA fingerprints you can optionally use with
DNSSEC/DANE.

Before running the script, you almost certainly will need to modify line number
19. You likely will need to change

    OPENSSL="/usr/bin/libressl"

to

    OPENSSL="/usr/bin/openssl"

You may also need to modify line 20.

Finally you will need to modify line 85-88 to put in your own information for
the cert, and then modify line 90 to enter your e-mail address.

Leave line 89 alone.

Once those mods are done, you can use the script to generate certs:

    sudo letsencrypt.sh example.org www.example.org support.example.org

That would create a single cert that is valid for all three of those domains.

The only downside is the certs are only valid for three months.

The private key will be put in `/etc/pki/tls/eff_private` and look something
like

    /etc/pki/tls/eff_private/trippyid.com-EFFLE-20180423.key

The certificate will be put in `/etc/pki/tls/eff_certs` and look something like

    /etc/pki/tls/eff_certs/trippyid.com-EFFLE-20180423.crt

The certificate authority bundle (sometimes called *chain*) will also be put in
`/etc/pki/tls/eff_certs` and look something like:

    /etc/pki/tls/eff_certs/trippyid.com-EFFLE-CAB-20180423.crt

Notice all three of those have a YYYYMMDD in them. When you renew, that is the
only part of the file name that will change - but __it will *not* change__ for
the private key unless a new private key needs to be generated because the
current one is outdated.

If you really want it updated more often, just edit line 51 and change the
`320` to something smaller, like say, `3`.

The reason I do not generate a new private key every time is because of key
pinning. I use [DANE](DNS-based_Authentication_of_Named_Entities) but the
same issue also exists with
[HPKP](https://en.wikipedia.org/wiki/HTTP_Public_Key_Pinning)

When you use a key pinning method, you need to rotate the new private key into
service and so it is much easier to only need to do that about once a year or
so.

### Certificate Update Strategy

With the shell script provided here, the apache daemon has to be shut down in
order to update the certificate. Unfortunately `certbot` does not allow you to
specify custom ports for the challenge/response system it uses to verify you
own the domain.

What I typically do (er, plan to do, I *just* wrote the script April 2018) is
shut down the server and run it for every host on the server I use with Let's
Encrypt.

When the virtual hosts are sub-domains of the same zone, I use a single cert
for all of them, but you do not have to. I do not like to share certs with
domains and sub-domains that are in different zones.

After I have generated all the new certs, which is very fast, I immediately
restart the server. The results in minimum downtime.

I then update the Apache configuration and if needed, DNS TLSA records at my
leisure, and only need to restart the server once they have all been updated to
reflect the new certificates.

Let's Encrypt certificates last for 3 months, do I generate new certs every 10
weeks giving me a little head room before the old certs have expired.

### DANE Rotation

If you do not use DNSSEC / DANE, this section is meaningless.

If you use DANE, I highly recommend you use the `3 1 1` version of the
fingerprint rather than the `3 0 1` version of the fingerprint.

With a `3 1 1` fingerprint, it is based on the public key, and that only
changes when the *private key* has changed.

With a `3 0 1` fingerprint, it changes every time the certificate changes
*regardless* of the private key. So do not use `3 0 1` TLSA records if you
do not want to have to constantly rotate TLSA records.

When the script does generate a new private key, before you put the new
certificate into service you need to add the TLSA record for the new
public key to your DNS zone. Keep the old TLSA record there too.

After at least three times the TTL for your TLSA record has passes, you can
then put the new certificate into service. I use a TTL of an hour, but I
usually wait an entire day before putting the new cert into service. That
gives plenty of time for the new TLSA record to propagate to caching name
servers.

Once the new cert is in service, you can remove the TLSA for the old private
key at your leisure.

### HPKP Rotation

I have a significant *disdain* for HPKP. It was pushed out by Google with no
academic review process and it has the danger of literally bricking your
website for many users.

__DO NOT USE IT.__

That being said, if you do, here is how to use it with this script.

After generating the initial key, you need to generate a backup key. HPKP
*requires* a backup key, so you will need to generate one:

    umask 0277
    pushd /etc/pki/tls/eff_private
    openssl genpkey -algorithm RSA -pkeyopt rsa_keygen_bits:3072 -out backup-yourdomain.tld

Obviously change `yourdomain.tld` to your domain name.

You can then create the needed keypins from the script generated private key
and the backup and configure apache to use them.

When it is time to generate a new private key, rename the backup to something
the shell script here will recognize *before* running the script, e.g.:

    umask 0277
    pushd /etc/pki/tls/eff_private
    mv backup-yourdomain.tld yourdomain.tld-EFFLE-YYYYMMDD.key
    touch yourdomain.tld-EFFLE-YYYYMMDD.key

The script will then recognize that as the key to use when generating a new
certificate. Create a new backup, and then update your apache configuration so
that it now sends the key pins for current private (old backup) and the new
backup.

Browsers that cached the keypins from before the change will accept the new
the certificate because it's keypin was cached when it was the backup, and
they will start caching the keypin for the new backup.

Please note that if both keys are compromised, you are fucked. If they are both
compromised, neither is suitable for service but browsers that cached them will
not accept your new keypins until their cache expires, bricking your website
for those users.

For that reason, it is often recommended to have a third backup keypin in your
HPKP configuration but with the key itself kept in a separate location where
not even your system administrators have access to it except in an emergency.

HPKP is a shit way of doing things. It is an extremely poorly thought out
system.

People only use it because Google has the monopoly power needed to push shit
technology on the world.

Oh they come up with some good things to, but but they come up with a lot of
shit that never would have been adopted if they were not Google.

If you have a mandate that says "implement HPKP or lose your job" you can do it
with Let's Encrypt with the this script, but if you do not have such a mandate,
don't bother with it.


Apache Log Configuration
------------------------

To be written. This will have instructions on how to set up apache to never log
tracking information.