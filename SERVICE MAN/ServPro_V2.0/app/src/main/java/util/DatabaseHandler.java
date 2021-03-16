package util;

import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;
import android.util.Log;

import java.text.DecimalFormat;
import java.text.NumberFormat;
import java.util.ArrayList;
import java.util.HashMap;

/**
 * Created by Rajesh on 2017-09-26.
 */

public class DatabaseHandler extends SQLiteOpenHelper {

    private static String DB_NAME = "ServPro";
    private static int DB_VERSION = 1;
    private SQLiteDatabase db;

    public static final String CART_TABLE = "cart";

    public static final String COLUMN_ID = "id";
    public static final String COLUMN_CAT_ID = "cat_id";
    public static final String COLUMN_TITLE = "title";
    public static final String COLUMN_NAME = "service_title";
    public static final String COLUMN_IMAGE = "service_icon";
    public static final String COLUMN_PRICE = "service_price";
    public static final String COLUMN_DISCOUNT = "service_discount";
    public static final String COLUMN_TIME = "service_approxtime";
    public static final String COLUMN_QTY = "qty";
    public static final String COLUMN_TOTAL_DISC_AMOUNT = "total_discount_amount";
    public static final String COLUMN_TOTAL_ITEM_PRICE = "total_item_price";
    public static final String COLUMN_BOOK_STATUS = "book_status";

    public DatabaseHandler(Context context) {
        super(context, DB_NAME, null, DB_VERSION);
    }

    @Override
    public void onCreate(SQLiteDatabase db) {
        this.db = db;

        String exe = "CREATE TABLE IF NOT EXISTS " + CART_TABLE
                + "(" + COLUMN_ID + " integer primary key, "
                + COLUMN_QTY + " DOUBLE NOT NULL,"
                + COLUMN_IMAGE + " TEXT NOT NULL, "
                + COLUMN_CAT_ID + " TEXT NOT NULL, "
                + COLUMN_NAME + " TEXT NOT NULL, "
                + COLUMN_PRICE + " DOUBLE NOT NULL, "
                + COLUMN_TIME + " DATETIME DEFAULT CURRENT_TIME, "
                + COLUMN_DISCOUNT + " DOUBLE NOT NULL, "
                + COLUMN_TOTAL_DISC_AMOUNT + " DOUBLE NOT NULL, "
                + COLUMN_TOTAL_ITEM_PRICE + " DOUBLE NOT NULL, "
                + COLUMN_BOOK_STATUS + " INTEGER NOT NULL, "
                + COLUMN_TITLE + " TEXT NOT NULL "
                + ")";

        db.execSQL(exe);
    }

    // set new data or update data if already existing in local cart table
    public boolean setCart(HashMap<String, String> map, Float Qty, Double Total_amount, Double Total_discount_amount) {
        db = getWritableDatabase();
        if (isInCart(map.get(COLUMN_ID))) {
            db.execSQL("update " + CART_TABLE + " set " +
                    COLUMN_TOTAL_DISC_AMOUNT + " = '" + Total_discount_amount + "'," +
                    COLUMN_TOTAL_ITEM_PRICE + " = '" + Total_amount + "'," +
                    COLUMN_QTY + " = '" + Qty + "' where " + COLUMN_ID + "=" + map.get(COLUMN_ID));
            return false;
        } else {
            ContentValues values = new ContentValues();

            values.put(COLUMN_ID, map.get(COLUMN_ID));
            values.put(COLUMN_QTY, Qty);
            values.put(COLUMN_CAT_ID, map.get(COLUMN_CAT_ID));
            values.put(COLUMN_IMAGE, map.get(COLUMN_IMAGE));
            values.put(COLUMN_TIME, map.get(COLUMN_TIME));
            values.put(COLUMN_NAME, map.get(COLUMN_NAME));
            values.put(COLUMN_PRICE, map.get(COLUMN_PRICE));
            values.put(COLUMN_TITLE, map.get(COLUMN_TITLE));
            values.put(COLUMN_DISCOUNT, map.get(COLUMN_DISCOUNT));
            values.put(COLUMN_BOOK_STATUS, map.get(COLUMN_BOOK_STATUS));
            values.put(COLUMN_TOTAL_DISC_AMOUNT, Total_discount_amount);
            values.put(COLUMN_TOTAL_ITEM_PRICE, Total_amount);

            db.insert(CART_TABLE, null, values);
            return true;
        }
    }

    // check specific item has in cart table
    public boolean isInCart(String id) {
        db = getReadableDatabase();
        String qry = "Select *  from " + CART_TABLE + " where " + COLUMN_ID + " = " + id;
        Cursor cursor = db.rawQuery(qry, null);
        cursor.moveToFirst();
        if (cursor.getCount() > 0) return true;

        return false;
    }

    // get total quntity of item from cart table
    public String getCartItemQty(String id) {
        db = getReadableDatabase();
        String qry = "Select *  from " + CART_TABLE + " where " + COLUMN_ID + " = " + id;
        Cursor cursor = db.rawQuery(qry, null);
        cursor.moveToFirst();
        if (cursor.getCount() > 0)
            return cursor.getString(cursor.getColumnIndex(COLUMN_QTY));
        else
            return "0";

    }

    public String getInCartItemQty(String id) {
        if (isInCart(id)) {
            db = getReadableDatabase();
            String qry = "Select *  from " + CART_TABLE + " where " + COLUMN_ID + " = " + id;
            Cursor cursor = db.rawQuery(qry, null);
            cursor.moveToFirst();
            return cursor.getString(cursor.getColumnIndex(COLUMN_QTY));
        } else {
            return "0.0";
        }
    }

    // get total cart item
    public int getCartCount() {
        db = getReadableDatabase();
        String qry = "Select *  from " + CART_TABLE + " where " + COLUMN_BOOK_STATUS + " = " + "1";
        Cursor cursor = db.rawQuery(qry, null);
        return cursor.getCount();
    }

    // count all itme price with qunitity and return total amount
    public String getTotalAmount() {
        db = getReadableDatabase();
        String qry = "Select SUM(" + COLUMN_QTY + " * " + COLUMN_PRICE + ") as total_amount  from " + CART_TABLE;
        Cursor cursor = db.rawQuery(qry, null);
        cursor.moveToFirst();
        String total = cursor.getString(cursor.getColumnIndex("total_amount"));
        if (total != null) {

            return total;
        } else {
            return "0";
        }
    }

    // get total item discount price
    public String getTotalDiscountAmount() {
        db = getReadableDatabase();
        String qry = "Select SUM(" + COLUMN_TOTAL_DISC_AMOUNT + ") as total_amount  from " + CART_TABLE;
        Cursor cursor = db.rawQuery(qry, null);
        cursor.moveToFirst();
        String total = cursor.getString(cursor.getColumnIndex("total_amount"));
        if (total != null) {

            return total;
        } else {
            return "0";
        }
    }

    // get all item from cart table which item has "book_status=1"
    public ArrayList<HashMap<String, String>> getCartAll() {
        ArrayList<HashMap<String, String>> list = new ArrayList<>();
        db = getReadableDatabase();
        String qry = "Select *  from " + CART_TABLE + " where " + COLUMN_BOOK_STATUS + " = " + "1";
        Cursor cursor = db.rawQuery(qry, null);
        cursor.moveToFirst();

        for (int i = 0; i < cursor.getCount(); i++) {
            HashMap<String, String> map = new HashMap<>();
            map.put(COLUMN_ID, cursor.getString(cursor.getColumnIndex(COLUMN_ID)));
            map.put(COLUMN_QTY, cursor.getString(cursor.getColumnIndex(COLUMN_QTY)));
            map.put(COLUMN_IMAGE, cursor.getString(cursor.getColumnIndex(COLUMN_IMAGE)));
            map.put(COLUMN_CAT_ID, cursor.getString(cursor.getColumnIndex(COLUMN_CAT_ID)));
            map.put(COLUMN_NAME, cursor.getString(cursor.getColumnIndex(COLUMN_NAME)));
            map.put(COLUMN_PRICE, cursor.getString(cursor.getColumnIndex(COLUMN_PRICE)));
            map.put(COLUMN_TIME, cursor.getString(cursor.getColumnIndex(COLUMN_TIME)));
            map.put(COLUMN_TITLE, cursor.getString(cursor.getColumnIndex(COLUMN_TITLE)));
            map.put(COLUMN_DISCOUNT, cursor.getString(cursor.getColumnIndex(COLUMN_DISCOUNT)));
            map.put(COLUMN_BOOK_STATUS, cursor.getString(cursor.getColumnIndex(COLUMN_BOOK_STATUS)));

            list.add(map);
            cursor.moveToNext();
        }
        return list;
    }

    public String getFavConcatString() {
        db = getReadableDatabase();
        String qry = "Select *  from " + CART_TABLE;
        Cursor cursor = db.rawQuery(qry, null);
        cursor.moveToFirst();
        String concate = "";
        for (int i = 0; i < cursor.getCount(); i++) {
            if (concate.equalsIgnoreCase("")) {
                concate = cursor.getString(cursor.getColumnIndex(COLUMN_ID));
            } else {
                concate = concate + "_" + cursor.getString(cursor.getColumnIndex(COLUMN_ID));
            }
            cursor.moveToNext();
        }
        return concate;
    }

    // delete cart table
    public void clearCart() {
        db = getReadableDatabase();
        db.execSQL("delete from " + CART_TABLE);
    }

    // delete item which has "book_status=0"
    public void deleteUnBookItems() {
        db = getReadableDatabase();
        db.execSQL("delete from " + CART_TABLE + " where " + COLUMN_BOOK_STATUS + " = " + "0");
    }

    // change status of book
    public void bookItems() {
        db = getReadableDatabase();
        db.execSQL("update " + CART_TABLE + " set " +
                COLUMN_BOOK_STATUS + " = '" + "1" + "' where " + COLUMN_BOOK_STATUS + "=" + "0");
    }

    // delete specifiec item form cart table
    public void removeItemFromCart(String id) {
        db = getReadableDatabase();
        db.execSQL("delete from " + CART_TABLE + " where " + COLUMN_ID + " = " + id);
    }

    // this function return total time
    public String getTotalTime(boolean isCart) {
        db = getReadableDatabase();
        String time = "00:00:00";

        String qry;

        qry = "Select *  from " + CART_TABLE;

        if (isCart) {
            qry = "Select *  from " + CART_TABLE + " where " + COLUMN_BOOK_STATUS + " = " + "1";
        }

        Log.d("Database", "getTotalTime: " + qry);

        Cursor cursor = db.rawQuery(qry, null);
        cursor.moveToFirst();

        for (int i = 0; i < cursor.getCount(); i++) {
            Integer qty = Integer.parseInt(cursor.getString(cursor.getColumnIndex(COLUMN_QTY)));
            for (int j = 0; j < qty; j++) {
                time = totalTime(time, cursor.getString(cursor.getColumnIndex(COLUMN_TIME)));
            }
            cursor.moveToNext();
        }

        return time;

    }

    @Override
    public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion) {

    }

    private String totalTime(String dateString, String addString) {
        NumberFormat f = new DecimalFormat("00");

        Log.e("Database", "totalTime: " + dateString + "," + addString);

        String[] time = dateString.split(":");
        int Hour = Integer.parseInt(time[0]);
        int Minut = Integer.parseInt(time[1]);
        int Second = Integer.parseInt(time[2]);
        String[] timeadd = addString.split(":");
        int HourAdd = Integer.parseInt(timeadd[0]);
        int MinutAdd = Integer.parseInt(timeadd[1]);
        int SecondAdd = Integer.parseInt(timeadd[2]);

        Second = Second + SecondAdd;
        if (Second > 60) {
            Minut = Minut + (Second / 60);
            Second = Second % 60;
        }
        Minut = Minut + MinutAdd;
        if (Minut > 60) {
            Hour = Hour + (Minut / 60);
            Minut = Minut % 60;
        }

        Hour = Hour + HourAdd;
        return f.format(Hour) + ":" + f.format(Minut) + ":" + f.format(Second);
    }

}
