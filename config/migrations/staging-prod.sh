wp db export local.sql
wp db import staging.sql
wp search-replace staging.fr prod.fr --all-tables 
wp option update blog_public 1
wp rewrite flush
wp db export prod.sql
wp db import local.sql