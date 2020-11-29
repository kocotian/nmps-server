# Configuration
To properly configure nmps, create in nmps parent directory file `nmpsdb.php` with contents:
```
<?php

    return
    [
        "host"     => "<DATABASE HOST>",
        "username" => "<DATABASE USERNAME>",
		"password" => "<DATABASE PASSWORD>",
        "database" => "<DATABASE NAME>"
    ];
```

File is in parent directory in terms of security.
