# myWebHook

A custom git web hook that allows you to automatically sync your repos with Github or Bitbucket. The web hook attempts 
to update a folder with the same name of the branch, but this can be configured easily.

## Concept

When using topic branching you can easily designate certain branches to represent application environments or locations.
Imagine a branch dedicated to your staging, development or production environment.

<img src="https://www.dropbox.com/s/kre58bltejl1oxh/myWebHookEnvironments.png?dl=1" height=340 />

> You can also associate a branch with multiple folders

<img src="https://www.dropbox.com/s/6tbhospuvkwzf2w/myWebHookClients.png?dl=1" height=340 />

## Installation

Simply download place the file `myWebHook.php` in your `www` or `public_html` directory on your hosting account.

> If you can SSH into your webhost and navigate to your public_html/ or www/ directory, simply run: `wget http://rawgithub.com/mexitek/myWebHook/master/myWebHook.php`

## Customization

Edit the first couple of variables in `myWebHook.php`. You can choose to change the name of your main git remote, 
turn on web hook logs or associate a branch with custom folder paths.

```php
// Your remote name
$remote = "origin";

// Aliases for branches and directories
$aliases = array(
  "master"  => array( "path/to/production" ),
  "staging" => "path/to/staging",
  "clients" => array( "client1","client2","client3","client4" )
);

// Do you want a log file with web hook posts?
$log = FALSE;
```

## Activate on Github

<img src="https://www.dropbox.com/s/avnm5e8gwcnguez/myWebHookGithubSetup.png?dl=1" />

## Activate on BitBucket

<img src="https://www.dropbox.com/s/xtl8uvs0epkfp7k/myWebHookBitbucketSetup.png?dl=1" />

# License: [arlo.mit-license.org](http://arlo.mit-license.org)
