#!/bin/bash

export DSL_TOKEN=$(curl https://app.dimensions.ai/api/auth.json -d '{"username": "ctomassini@csic.edu.uy", "password": "proyectoanii2019"}' -s|jq -r .token)
curl https://app.dimensions.ai/api/dsl.json -H "Authorization: JWT $DSL_TOKEN" -d 'search publications where research_org_country_names="Uruguay" return publications[all] limit 1000  skip 0' -s|jq . > /var/www/html/wscvuy/dimensionsFiles/dataFull0.json
sleep 3
curl https://app.dimensions.ai/api/dsl.json -H "Authorization: JWT $DSL_TOKEN" -d 'search publications where research_org_country_names="Uruguay" return publications[all] limit 1000  skip 1000' -s|jq . > /var/www/html/wscvuy/dimensionsFiles/dataFull1.json
sleep 3
curl https://app.dimensions.ai/api/dsl.json -H "Authorization: JWT $DSL_TOKEN" -d 'search publications where research_org_country_names="Uruguay" return publications[all] limit 1000  skip 2000' -s|jq . > /var/www/html/wscvuy/dimensionsFiles/dataFull2.json
sleep 3
curl https://app.dimensions.ai/api/dsl.json -H "Authorization: JWT $DSL_TOKEN" -d 'search publications where research_org_country_names="Uruguay" return publications[all] limit 1000  skip 3000' -s|jq . > /var/www/html/wscvuy/dimensionsFiles/dataFull3.json
sleep 3
curl https://app.dimensions.ai/api/dsl.json -H "Authorization: JWT $DSL_TOKEN" -d 'search publications where research_org_country_names="Uruguay" return publications[all] limit 1000  skip 4000' -s|jq . > /var/www/html/wscvuy/dimensionsFiles/dataFull4.json
sleep 3
curl https://app.dimensions.ai/api/dsl.json -H "Authorization: JWT $DSL_TOKEN" -d 'search publications where research_org_country_names="Uruguay" return publications[all] limit 1000  skip 5000' -s|jq . > /var/www/html/wscvuy/dimensionsFiles/dataFull5.json
sleep 3
curl https://app.dimensions.ai/api/dsl.json -H "Authorization: JWT $DSL_TOKEN" -d 'search publications where research_org_country_names="Uruguay" return publications[all] limit 1000  skip 6000' -s|jq . > /var/www/html/wscvuy/dimensionsFiles/dataFull6.json
sleep 3
curl https://app.dimensions.ai/api/dsl.json -H "Authorization: JWT $DSL_TOKEN" -d 'search publications where research_org_country_names="Uruguay" return publications[all] limit 1000  skip 7000' -s|jq . > /var/www/html/wscvuy/dimensionsFiles/dataFull7.json
sleep 3
curl https://app.dimensions.ai/api/dsl.json -H "Authorization: JWT $DSL_TOKEN" -d 'search publications where research_org_country_names="Uruguay" return publications[all] limit 1000  skip 8000' -s|jq . > /var/www/html/wscvuy/dimensionsFiles/dataFull8.json
sleep 3
curl https://app.dimensions.ai/api/dsl.json -H "Authorization: JWT $DSL_TOKEN" -d 'search publications where research_org_country_names="Uruguay" return publications[all] limit 1000  skip 9000' -s|jq . > /var/www/html/wscvuy/dimensionsFiles/dataFull9.json
sleep 3
curl https://app.dimensions.ai/api/dsl.json -H "Authorization: JWT $DSL_TOKEN" -d 'search publications where research_org_country_names="Uruguay" return publications[all] limit 1000  skip 10000' -s|jq . > /var/www/html/wscvuy/dimensionsFiles/dataFull10.json
sleep 3
curl https://app.dimensions.ai/api/dsl.json -H "Authorization: JWT $DSL_TOKEN" -d 'search publications where research_org_country_names="Uruguay" return publications[all] limit 1000  skip 11000' -s|jq . > /var/www/html/wscvuy/dimensionsFiles/dataFull11.json
sleep 3
curl https://app.dimensions.ai/api/dsl.json -H "Authorization: JWT $DSL_TOKEN" -d 'search publications where research_org_country_names="Uruguay" return publications[all] limit 1000  skip 12000' -s|jq . > /var/www/html/wscvuy/dimensionsFiles/dataFull12.json
sleep 3
curl https://app.dimensions.ai/api/dsl.json -H "Authorization: JWT $DSL_TOKEN" -d 'search publications where research_org_country_names="Uruguay" return publications[all] limit 1000  skip 13000' -s|jq . > /var/www/html/wscvuy/dimensionsFiles/dataFull13.json
sleep 3
curl https://app.dimensions.ai/api/dsl.json -H "Authorization: JWT $DSL_TOKEN" -d 'search publications where research_org_country_names="Uruguay" return publications[all] limit 1000  skip 14000' -s|jq . > /var/www/html/wscvuy/dimensionsFiles/dataFull14.json
sleep 3
curl https://app.dimensions.ai/api/dsl.json -H "Authorization: JWT $DSL_TOKEN" -d 'search publications where research_org_country_names="Uruguay" return publications[all] limit 1000  skip 15000' -s|jq . > /var/www/html/wscvuy/dimensionsFiles/dataFull15.json
sleep 3
curl https://app.dimensions.ai/api/dsl.json -H "Authorization: JWT $DSL_TOKEN" -d 'search publications where research_org_country_names="Uruguay" return publications[all] limit 1000  skip 16000' -s|jq . > /var/www/html/wscvuy/dimensionsFiles/dataFull16.json
sleep 3
curl https://app.dimensions.ai/api/dsl.json -H "Authorization: JWT $DSL_TOKEN" -d 'search publications where research_org_country_names="Uruguay" return publications[all] limit 1000  skip 17000' -s|jq . > /var/www/html/wscvuy/dimensionsFiles/dataFull17.json
sleep 3
curl https://app.dimensions.ai/api/dsl.json -H "Authorization: JWT $DSL_TOKEN" -d 'search publications where research_org_country_names="Uruguay" return publications[all] limit 1000  skip 18000' -s|jq . > /var/www/html/wscvuy/dimensionsFiles/dataFull18.json
sleep 3
curl https://app.dimensions.ai/api/dsl.json -H "Authorization: JWT $DSL_TOKEN" -d 'search publications where research_org_country_names="Uruguay" return publications[all] limit 1000  skip 19000' -s|jq . > /var/www/html/wscvuy/dimensionsFiles/dataFull19.json
sleep 3
curl https://app.dimensions.ai/api/dsl.json -H "Authorization: JWT $DSL_TOKEN" -d 'search publications where research_org_country_names="Uruguay" return publications[all] limit 1000  skip 20000' -s|jq . > /var/www/html/wscvuy/dimensionsFiles/dataFull20.json
sleep 3
curl https://app.dimensions.ai/api/dsl.json -H "Authorization: JWT $DSL_TOKEN" -d 'search publications where research_org_country_names="Uruguay" return publications[all] limit 1000  skip 21000' -s|jq . > /var/www/html/wscvuy/dimensionsFiles/dataFull21.json
sleep 3
curl https://app.dimensions.ai/api/dsl.json -H "Authorization: JWT $DSL_TOKEN" -d 'search publications where research_org_country_names="Uruguay" return publications[all] limit 1000  skip 22000' -s|jq . > /var/www/html/wscvuy/dimensionsFiles/dataFull22.json
sleep 3
curl https://app.dimensions.ai/api/dsl.json -H "Authorization: JWT $DSL_TOKEN" -d 'search publications where research_org_country_names="Uruguay" return publications[all] limit 1000  skip 23000' -s|jq . > /var/www/html/wscvuy/dimensionsFiles/dataFull23.json
sleep 3
curl https://app.dimensions.ai/api/dsl.json -H "Authorization: JWT $DSL_TOKEN" -d 'search publications where research_org_country_names="Uruguay" return publications[all] limit 1000  skip 24000' -s|jq . > /var/www/html/wscvuy/dimensionsFiles/dataFull24.json
sleep 3
curl https://app.dimensions.ai/api/dsl.json -H "Authorization: JWT $DSL_TOKEN" -d 'search publications where research_org_country_names="Uruguay" return publications[all] limit 1000  skip 25000' -s|jq . > /var/www/html/wscvuy/dimensionsFiles/dataFull25.json
sleep 3
curl https://app.dimensions.ai/api/dsl.json -H "Authorization: JWT $DSL_TOKEN" -d 'search publications where research_org_country_names="Uruguay" return publications[all] limit 1000  skip 26000' -s|jq . > /var/www/html/wscvuy/dimensionsFiles/dataFull26.json
sleep 3
curl https://app.dimensions.ai/api/dsl.json -H "Authorization: JWT $DSL_TOKEN" -d 'search publications where research_org_country_names="Uruguay" return publications[all] limit 1000  skip 27000' -s|jq . > /var/www/html/wscvuy/dimensionsFiles/dataFull27.json
sleep 3
curl https://app.dimensions.ai/api/dsl.json -H "Authorization: JWT $DSL_TOKEN" -d 'search publications where research_org_country_names="Uruguay" return publications[all] limit 1000  skip 28000' -s|jq . > /var/www/html/wscvuy/dimensionsFiles/dataFull28.json
sleep 3
curl https://app.dimensions.ai/api/dsl.json -H "Authorization: JWT $DSL_TOKEN" -d 'search publications where research_org_country_names="Uruguay" return publications[all] limit 1000  skip 29000' -s|jq . > /var/www/html/wscvuy/dimensionsFiles/dataFull29.json
sleep 3
curl https://app.dimensions.ai/api/dsl.json -H "Authorization: JWT $DSL_TOKEN" -d 'search publications where research_org_country_names="Uruguay" return publications[all] limit 1000  skip 30000' -s|jq . > /var/www/html/wscvuy/dimensionsFiles/dataFull30.json
sleep 3
curl https://app.dimensions.ai/api/dsl.json -H "Authorization: JWT $DSL_TOKEN" -d 'search publications where research_org_country_names="Uruguay" return publications[all] limit 1000  skip 31000' -s|jq . > /var/www/html/wscvuy/dimensionsFiles/dataFull31.json
