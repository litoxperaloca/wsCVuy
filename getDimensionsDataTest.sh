#!/bin/bash

export DSL_TOKEN=$(curl https://app.dimensions.ai/api/auth.json -d '{"username": "ctomassini@csic.edu.uy", "password": "proyectoanii2019"}' -s|jq -r .token)
curl https://app.dimensions.ai/api/dsl.json -H "Authorization: JWT $DSL_TOKEN" -d 'search publications where research_org_country_names="Uruguay" return publications[all] limit 100  skip 0' -s|jq . > /var/www/html/wscvuy/dimensionsFiles/dataFullTest.json
