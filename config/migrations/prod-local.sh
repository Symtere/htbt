wp db export local.sql
wp db import prod.sql
wp search-replace prod.fr  --all-tables 
wp option update blog_public 0
wp rewrite flush