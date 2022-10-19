# INFO

```
Transfer data from existing API_ORDERS database to new FESP MySQL database,
and modify so that product details (title, variations, price) no longer
need to be added to every order. This has greatly reduced the required
database disk space.
```

## Updated 'PLATFORM_items' tables

title, variations and price fields have been removed

orderId|itemId|sku|qty|shipping
---|---|---|---|---
026-0050977-9828368|B09FFGK2TL|Playground-Sand_25_3|1|0
026-0177135-6212329|B096Y3GBNY|Dead_Sea_Table_(Bath)-20kg-tub|1|0
026-0197970-8989130|B073FR61TB|splitcane_600_10|1|0
etc.

A new table has been created (lookup_title_variation_price) that now stores the removed fields from the 'PLATFORM_items' tables

platform|date|sku|title|variation|price
---|---|---|---|---|---
am|1666004366|Playground-Sand_25_3|Elixir Gardens Playground Surface Sand 25 kg Bag | Children’s Play Sand, Non Toxic, Natural Washed and Graded | Sandpit Sand for Kids|{"Size":"25kg Bag x 3"}|36.99
am|1666028473|Dead_Sea_Table_(Bath)-20kg-tub|Elixir Gardens Dead Sea Salt | Organic 100% Natural Salts | Various Sizes 250g-25kg|{"Size":"20kg Tub"}|21.99
am|1666031945|splitcane_600_10|Elixir Gardens Green Split Canes Support Sticks Plant Garden Lily Bulb Flower 12 24 & 36 Inch|{"PackageQuantity":"10","Size":"2ft"}|4.99
etc.

The advantage of this is that they're no longer duplicated with every order.