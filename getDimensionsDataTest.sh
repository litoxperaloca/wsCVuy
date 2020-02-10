#!/bin/bash

#SUSTITUIR username y password por las credenciales otorgadas por dimensions API para extraer datos de dimensions y con eso obtener las instituciones de los autores de los artículos
export DSL_TOKEN=$(curl https://app.dimensions.ai/api/auth.json -d '{"username": "XXXXXXXX", "password": "XXXXXX"}' -s|jq -r .token)
curl https://app.dimensions.ai/api/dsl.json -H "Authorization: JWT $DSL_TOKEN" -d 'search publications where research_org_country_names="Uruguay" return publications[all] limit 100  skip 0' -s|jq . > /var/www/html/wscvuy/dimensionsFiles/dataFullTest.json
