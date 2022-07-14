#include <stdio.h>
#include <string.h>
#include <mysql.h>


int initDB(void *pMysql) {
    /* Initialiser la structure MySQL */
    if ((pMysql = mysql_init(NULL)) == NULL)
    {fprintf(stderr, "mysql_init(): %s\n", mysql_error(pMysql)); return -1;}
}

int connectDB(void *pMysql) {
    const char HOTE[30]={"HOTE"};
    const char NOM_BASE[30]={"NOM_BASE"};
    const char USER[30]={"USER"};
    const char PASSWORD[30]={"PASSWORD"};
    readConfigFile(HOTE);
    readConfigFile(NOM_BASE);
    readConfigFile(USER);
    readConfigFile(PASSWORD);

    /* Se connecter a base de donnees */
    if (mysql_real_connect(pMysql, HOTE, USER, PASSWORD, NOM_BASE, 0, NULL, 0) == NULL)
    {
        printf("Une erreur s'est produite lors de la connexion à la BDD! \n");
        fprintf(stderr, "mysql_real_connect(): %s\n", mysql_error(pMysql));  mysql_close(pMysql); return -1;}
    else
    {
        printf("Connexion BDD ok\n");
    }
}

int executeRequestDB(void *pMysql, char * request){
    /* Executer la requete : inserer un element */

    if (mysql_real_query(pMysql,request,strlen(request)) != 0) {
        fprintf(stderr, "mysql_real_query(): %s\n", mysql_error(pMysql));  mysql_close(pMysql); return -1;
    }else{
        //printf("\n Execution requete ok \n");
    };
}

void updateWeatherBD(void *pMysql, char *date[20], double tempC, char *description[100], int heure){
    char requestUpdateWeather[255];
    sprintf(requestUpdateWeather,"UPDATE `caf_meteo` SET `date`='%s', `temperature`=%.1lf,`description`='%s' WHERE heure=%d",date,tempC,description,heure);
    executeRequestDB(pMysql,requestUpdateWeather);

}

void initTableMeteo(void *pMysql){
    char RequestInitTableMeteo[255];
    int i=0;
    int j=0;
    for (i=0; i<8; i++){
        sprintf(RequestInitTableMeteo,"INSERT INTO `caf_meteo`(heure) VALUES (%d)",j);
        executeRequestDB(pMysql,RequestInitTableMeteo);
        j+=3;
    }

}

int closeDB(void *pMysql) {
    /* Fermer la connexion a la base */
    mysql_close(pMysql);
}
