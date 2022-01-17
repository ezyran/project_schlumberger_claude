./vendor/bin/doctrine orm:convert-mapping --namespace="" --from-database xml ./config/yml

./vendor/bin/doctrine orm:generate-entities --generate-annotations=false --update-entities=true ./src

./vendor/bin/doctrine orm:schema-tool:update --force --dump-sql 

./vendor/bin/doctrine orm:validate-schema

./vendor/bin/doctrine orm:clear-cache:metadata