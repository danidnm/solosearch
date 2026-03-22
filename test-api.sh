curl -X POST http://api.st.com/api/v1/feed \
     -H "X-Auth-Token: 68475d64026545cd15d1580284338941" \
     -H "Content-Type: application/json" \
     -d '{
           "channel": "google_shopping",
           "data": {
             "id": "123",
             "title": "Producto de prueba"
           }
         }'
