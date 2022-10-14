# Pseudo Design Code

## Undispatched Orders View

```shell
Class UndispatchedController

# Calculate undispatched orders for tabular display
    # NOTE - Print/Scan times come from the BARCODE database:
    #   Date time (Print) = barcodeGenerated TS
    #   Date time (Scan) = statusTime TS

    # All void orders from last 180 days
    SELECT orderID FROM BARCODE TABLE WHERE
        status FLD = VOID AND
        statusTime FLD > 180 days ago
        
    ASSIGN RESULTS TO $barcode_void ARRAY


    # All orders previously dispatched
    SELECT orderID FROM dispatched_orders TABLE WHERE
        timestamp FLD > 180 days ago

    ASSIGN RESULTS TO $dispatched_orders ARRAY


    # All non void orders from last 14 days
    SELECT orderID FROM ORDERS TABLE WHERE
        orderID FLD NOT IN $barcode_void ARRAY AND
        orderID FLD NOT IN $dispatched_orders ARRAY AND
        source FLD != manual AND
        dateRetrieved FLD > 14 days ago

    ASSIGN RESULTS TO $undispatched_orders ARRAY
```



```shell
# EXAMPLE PSEUDO CODE
#--------------------
# Enter Customer_Name
# SEEK Customer_Name in Customer_Name_DB file
# IF Customer_Name found THEN
#    Call procedure USER_PASSWORD_AUTHENTICATE()
# ELSE
#    PRINT error message
#    Call procedure NEW_CUSTOMER_REQUEST()
# ENDIF
```