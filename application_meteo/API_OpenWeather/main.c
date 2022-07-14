#include <stdio.h>
#include <stdlib.h>
#include <mysql.h>

int main()
{
    char cut[1000][100];

    printf("Execution du code...\n");


    MYSQL *pMysql;
    pMysql = mysql_init(NULL);


    initDB(pMysql);
    connectDB(pMysql);

    //recup le json de l'API
    downloadInfoAPIWeather();
    //trier le json (decouper en tableau
    sortJson(cut);
    //afficher en console
    //printSortJson(cut);

    //A EXECUTER UNE SEULE FOIS POUR INIT LA TABLE
    //initTableMeteo(pMysql);


    getInfoFromWeather(cut);
    closeDB(pMysql);

    // scanf("%d", &cut);
    //printf(" je suis un e accent : %c\n", 130);
    return 0;
}
