wp option update blog_public 0
wp db export local.sql
wp search-replace  staging.fr --all-tables 
wp rewrite flush
wp db export staging.sql
wp db import local.sql