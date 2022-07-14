package com.example.trotterfly;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;

public class Preferences {

    private static final String SHARED_PREF_NAME = "trotterfly";
    private static final String USERNAME = "username";
    private static final String EMAIL = "email";
    private static final String GENDER = "gender";
    private static final String ID = "id";

    private static Preferences mInstance;
    private static Context mCtx;

    private Preferences(Context context) {
        mCtx = context;
    }

    public static synchronized Preferences getInstance(Context context) {
        if (mInstance == null) {
            mInstance = new Preferences(context);
        }
        return mInstance;
    }

    // connexion user
    // stockage du user dans la sharedpref
    public void userLogin(User user) {
        SharedPreferences sharedPreferences = mCtx.getSharedPreferences(SHARED_PREF_NAME, Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.putInt(ID, user.getId());
        editor.putString(USERNAME, user.getUsername());
        editor.putString(EMAIL, user.getEmail());
        editor.putString(GENDER, user.getGender());
        editor.apply();
    }

    // vérif connecté ou non
    public boolean isLoggedIn() {
        SharedPreferences sharedPreferences = mCtx.getSharedPreferences(SHARED_PREF_NAME, Context.MODE_PRIVATE);
        return sharedPreferences.getString(USERNAME, null) != null;
    }

    // équivalent whoami
    public User getUser() {
        SharedPreferences sharedPreferences = mCtx.getSharedPreferences(SHARED_PREF_NAME, Context.MODE_PRIVATE);
        return new User(
                sharedPreferences.getInt(ID, -1),
                sharedPreferences.getString(USERNAME, null),
                sharedPreferences.getString(EMAIL, null),
                sharedPreferences.getString(GENDER, null)
        );
    }

    //déconnexion utilisateur
    public void logout() {
        SharedPreferences sharedPreferences = mCtx.getSharedPreferences(SHARED_PREF_NAME, Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.clear();
        editor.apply();
        mCtx.startActivity(new Intent(mCtx, LoginActivity.class));
    }

}
