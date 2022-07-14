#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <curl/curl.h>
#include <mysql.h>



void readConfigFile(char * object){
    FILE *fp;
    char line[200];

    fp = fopen("config.txt","rt");
    if(fp == NULL) return 1;

    while (fgets(line, 2000, fp) != NULL) {
        //printf("%s",line);

        char search[100];
        sprintf(search,"%s:",object);

        char * result = strstr( line, search );
        if (result != NULL){

            char *p = strchr(result, ':');
            if (p != NULL){
                strcpy(object, p + 1);
            }
        }
    }
    inputString(object,0);

    fclose(fp);
    return 0;
}


void inputString(char *string, int size) {
    fflush(stdin);
    if(size != 0 ){
        fgets(string, size, stdin);
    }
    if (string[strlen(string) - 1] == '\n')
        string[strlen(string) - 1] = '\0';
}



void downloadInfoAPIWeather(){
    CURL *curl;
    FILE *fp;
    int result;
    char url[300]={"url"};

    readConfigFile(url);
    //printf("url recup : %s", url);
    inputString(url,0);
    fp = fopen("meteoLyonJson.txt", "wb");

    curl = curl_easy_init();
    curl_easy_setopt(curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_easy_setopt(curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_easy_setopt(curl, CURLOPT_URL, url);
    curl_easy_setopt(curl, CURLOPT_WRITEDATA, fp);
    curl_easy_setopt(curl, CURLOPT_FAILONERROR, 1L);


    result= curl_easy_perform(curl);

    if(result == CURLE_OK)
        printf("Telechargement reussi !\n");
    else
        printf("ERROR : %s\n", curl_easy_strerror(result));

    fclose(fp);

    curl_easy_cleanup(curl);
    //ouvrir le fichier
    //system( "meteoLyonJson.txt" );
    return 0;
}


//tri le json
void sortJson(char cut[1000][100]){
    FILE * fp;
    char reads[5000];
    char clean[1000][1000];

    //fichier a trier (ici, resultat du json de l'API)
    fp = fopen("meteoLyonJson.txt", "rb" );
    if (fp ==NULL) return 1;


    //compter le nb d'octet d'un fichier
    int size;
    if (fp == NULL) return -1;
        fseek(fp, 0, SEEK_END) ;
        size = ftell(fp);
        //printf("size = %ld", size);

    //on remet le curseur a 0 puis fichier => reads
    //size = nbr d'octets du fichier
    fseek(fp, 0, SEEK_SET) ;
    fread(reads, sizeof(char), size, fp);

    //afficher le fichier
    //printf("\n%s", reads);

    strTokResult(cut, reads);
//    storeIntoClean(cut[50][50], clean[50][50]);
    return 0;
}

// chaque fragment sera stocké dans une case d'un tableau
void strTokResult(char cut[1000][100], char *result){
    const char s[10] = "\"\:,{}[]";
    char* tok;
    int i=0;

    tok = strtok(result, s);
    while (tok != 0) {
        ++i;
        strcpy(cut[i], tok);
        tok = strtok(0, s);
    }
    //ajout de la ligne de fin
    strcpy(cut[i+1],"codeDeFinTableau419376");



}
void printSortJson(char cut[1000][100]){
    int i=0;
    while(strcmp(cut[i],"codeDeFinTableau419376")!=0){
        printf("\n[%d] %s",i,cut[i]);
        i++;
    }
}


void getInfoFromWeather(char cut[1000][100]){
    MYSQL *pMysql;
    pMysql = mysql_init(NULL);
    initDB(pMysql);
    connectDB(pMysql);

    int i=0;
    int j=0;

    char description[50];

    char tempChar[50];
    double tempK;
    double tempC;

    char x[50];
    int year;
    int month;
    int day;
    int hour;

    char heure[20];
    char date[20];


    printf("\n\nVille : Lyon");
    while(strcmp(cut[i],"codeDeFinTableau419376")!=0){

            //temperature
            if(strcmp(cut[i],"temp")==0){
                strcpy(tempChar,cut[i+1]);
                tempK=atof(tempChar);//convert double
                tempC=tempK-273.15;// convert °C
                printf("\n[%d] : %s : %.1lf C",i+1 ,cut[i] , tempC);
            }

            //description
            if(strcmp(cut[i],"description")==0){
                strcpy(description, cut[i-1]);
                printf("\n[%d] : %s : %s",i+1 ,cut[i] , description);
            }

            //date+heure
            if(strcmp(cut[i],"dt_txt")==0){
                sprintf(x,"%c%c%c%c",cut[i+1][0],cut[i+1][1],cut[i+1][2],cut[i+1][3]);
                year=atoi(x);

                sprintf(x,"%c%c",cut[i+1][5],cut[i+1][6]);
                month=atoi(x);

                sprintf(x,"%c%c",cut[i+1][8],cut[i+1][9]);
                day=atoi(x);

                sprintf(x,"%c%c",cut[i+1][11],cut[i+1][12]);
                hour=atoi(x);


                sprintf(date, "%d-%d-%d", year, month, day);

                printf("\n[%d] : %s : %s %dh",i+1 ,cut[i] , date , hour);
                printf("\n");


                //update BDD
                updateWeatherBD(pMysql,date,tempC,description,hour);


            }
        i++;
    }



}











