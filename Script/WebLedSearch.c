// WebLedSearch.cpp : 定义控制台应用程序的入口点。
//
#include "sqlite3.h"
#include <stdio.h>
#include <string.h>
#include <stdlib.h>
#ifdef WIN32
#include <Windows.h>
#endif

#define MAX_SQL 260

char g_szNetCard[MAX_SQL];			// 网络卡型号
char g_szWifiCard[MAX_SQL];			// Wifi型号
char g_szUSBCard[MAX_SQL];			// U盘卡

// 获取最终信息
int SerachInfo(char* argv[], int p_iWidth, int p_iHeight, const char* p_szScanPort, const char* p_szVersion, sqlite3* p_sqlite)
{
	sqlite3_stmt *stmt = NULL;
	int iRet = 1;
	int iWidth = 0;
	int iHeight = 0;
	int iNum = 1;
	char szSql[MAX_SQL*10] = {0};	// SQL语句

	if (strcmp(p_szScanPort,"8") == 0)
	{
		iNum = 2;
	}

	if (strcmp(argv[3],"D") == 0)		// 如果是按点数计算
	{
		iWidth = atoi(argv[4]);
		iHeight = atoi(argv[5]);
		if (iWidth <1||iHeight<1)
		{
			return 1;
		}
		iWidth = iWidth/p_iWidth;
		iHeight = iHeight/p_iHeight/iNum;
		if (iWidth == 0)
		{
			iWidth = 1;
		}

		if (iHeight == 0)
		{
			iHeight = 1;
		}
	}
	else
	{
		iWidth = atoi(argv[3]);
		iHeight = atoi(argv[4]);
		if (iWidth <1||iHeight<1)
		{
			return 1;
		}
	}
	if (iNum == 2)
	{
		// 08
		sprintf(szSql,"SELECT * from VersionInfo WHERE Version = '%s' \
					  and EightPortNum >= %d \
					  and VersionWidth >= %d \
					  and VersionMax >=%d",
					  p_szVersion,iHeight,iWidth*p_iWidth,iWidth*p_iWidth*iHeight*p_iHeight*2);
	}
	else
	{
		// 12
		sprintf(szSql,"SELECT * from VersionInfo WHERE Version = '%s' \
					  and TwelvePortNum >= %d \
					  and VersionWidth >= %d \
					  and VersionMax >=%d",
					  p_szVersion,iHeight,iWidth*p_iWidth,iWidth*p_iWidth*iHeight*p_iHeight);
	}

	if (sqlite3_prepare(p_sqlite, szSql, -1, &stmt, 0) == SQLITE_OK) {

		while (sqlite3_step(stmt) == SQLITE_ROW) {
			iRet = 0;
			break;
		}

		sqlite3_finalize(stmt);
	} else {
		printf("Search Error:\n[-] sqlite3_prepare_v2(\"%s\") : %s\n",
			szSql, sqlite3_errmsg(p_sqlite));
		iRet = 3;
	}



	return iRet;
}

// 查询端口信息
int SearchPortInfo(char* argv[], int p_iWidth, int p_iHeight, const char* p_szScanPort, const char* p_szVersion, sqlite3* p_sqlite)
{
	sqlite3_stmt *stmt = NULL;
	int iRet = 0;
	char szSql[MAX_SQL*10] = {0};	// SQL语句

	char szColor[MAX_SQL] = {0};	// 颜色
	if (strcmp(argv[1],"1") == 0)
	{
#ifdef WIN32
		WideCharToMultiByte( CP_UTF8, 0, L"单双色", -1,
			szColor, MAX_SQL, NULL, NULL );
#else
		strcpy(szColor,"单双色");
#endif
	}
	else if (strcmp(argv[1],"2") == 0)
	{
#ifdef WIN32
		WideCharToMultiByte( CP_UTF8, 0, L"七彩", -1,
			szColor, MAX_SQL, NULL, NULL );
#else
		strcpy(szColor,"七彩");
#endif
	}
	else if (strcmp(argv[1],"3") == 0)
	{
#ifdef WIN32
		WideCharToMultiByte( CP_UTF8, 0, L"七彩灰度", -1,
			szColor, MAX_SQL, NULL, NULL );
#else
		strcpy(szColor,"七彩灰度");
#endif
	}

	

	// 网络
	if (g_szNetCard[0] == 0)
	{
		sprintf(szSql,"select Version from VersionInfo where Version=  '%s' and Net = '1' and VersionColor = '%s' order by VersionDescribion",p_szVersion,szColor);
		// execute SQL statement
		if (sqlite3_prepare(p_sqlite, szSql, -1, &stmt, 0) == SQLITE_OK) {

			while (sqlite3_step(stmt) == SQLITE_ROW) {
				iRet = SerachInfo(argv,p_iWidth,p_iHeight,p_szScanPort,(const char*)sqlite3_column_text(stmt,0),p_sqlite);
				if (!iRet)
				{
					strcpy(g_szNetCard,(const char*)sqlite3_column_text(stmt,0));
					break;
				}
			}

			sqlite3_finalize(stmt);
		} else {
			printf("Search Error:\n[-] sqlite3_prepare_v2(\"%s\") : %s\n",
				szSql, sqlite3_errmsg(p_sqlite));
			iRet = 3;
		}
	}
	

// 	// 串口
// 	sprintf(szSql,"select Version from VersionInfo where Version= '%s' and Serial  = '1' order by VersionDescribion",p_szVersion);
// 	// execute SQL statement
// 	if (sqlite3_prepare(p_sqlite, szSql, -1, &stmt, 0) == SQLITE_OK) {
// 
// 		while (sqlite3_step(stmt) == SQLITE_ROW) {
// 			iRet = SerachInfo(argv,p_iWidth,p_iHeight,p_szScanPort,(const char*)sqlite3_column_text(stmt,0),p_sqlite);
			//if (!iRet)
			//{
			//	break;
			//}
// 		}
// 
// 		sqlite3_finalize(stmt);
// 	} else {
// 		printf("Search Error:\n[-] sqlite3_prepare_v2(\"%s\") : %s\n",
// 			szSql, sqlite3_errmsg(p_sqlite));
// 		iRet = 3;
// 	}

	// U盘
	if (g_szUSBCard[0] == 0)
	{
		sprintf(szSql,"select Version from VersionInfo where Version= '%s' and VersionColor = '%s'  order by VersionDescribion",p_szVersion,szColor);
		// execute SQL statement
		if (sqlite3_prepare(p_sqlite, szSql, -1, &stmt, 0) == SQLITE_OK) {

			while (sqlite3_step(stmt) == SQLITE_ROW) {
				iRet = SerachInfo(argv,p_iWidth,p_iHeight,p_szScanPort,(const char*)sqlite3_column_text(stmt,0),p_sqlite);
				if (!iRet)
				{

					strcpy(g_szUSBCard,(const char*)sqlite3_column_text(stmt,0));
					break;
				}
			}


			sqlite3_finalize(stmt);
		} else {
			printf("Search Error:\n[-] sqlite3_prepare_v2(\"%s\") : %s\n",
				szSql, sqlite3_errmsg(p_sqlite));
			iRet = 3;
		}
	}
	

	// Wifi
	if (g_szWifiCard[0] == 0)
	{
		sprintf(szSql,"select Version from VersionInfo where Version= '%s' and VersionColor = '%s'  and WIFI = '1' order by VersionDescribion",p_szVersion,szColor);
		// execute SQL statement
		if (sqlite3_prepare(p_sqlite, szSql, -1, &stmt, 0) == SQLITE_OK) {

			while (sqlite3_step(stmt) == SQLITE_ROW) {
				iRet = SerachInfo(argv,p_iWidth,p_iHeight,p_szScanPort,(const char*)sqlite3_column_text(stmt,0),p_sqlite);
				if (!iRet)
				{

					strcpy(g_szWifiCard,(const char*)sqlite3_column_text(stmt,0));
					break;
				}
			}

			sqlite3_finalize(stmt);
		} else {
			printf("Search Error:\n[-] sqlite3_prepare_v2(\"%s\") : %s\n",
				szSql, sqlite3_errmsg(p_sqlite));
			iRet = 3;
		}
	}
	
	return 0;
}

// 查询扫描型号
int SearchVersion_Scan(char* argv[], int p_iWidth, int p_iHeight, const char* p_szScanPort, int p_iScanType, sqlite3* p_sqlite)
{
	sqlite3_stmt *stmt = NULL;
	int iRet = 0;
	char szSql[MAX_SQL*10] = {0};	// SQL语句

	sprintf(szSql,"select Version from Version_Scan where Scan =%d",p_iScanType);

	// execute SQL statement
	if (sqlite3_prepare(p_sqlite, szSql, -1, &stmt, 0) == SQLITE_OK) {

		while (sqlite3_step(stmt) == SQLITE_ROW) {
			iRet = SearchPortInfo(argv,p_iWidth,p_iHeight,p_szScanPort,(const char*)sqlite3_column_text(stmt,0),p_sqlite);
		}

		sqlite3_finalize(stmt);
	} else {
		printf("Search Error:\n[-] sqlite3_prepare_v2(\"%s\") : %s\n",
			szSql, sqlite3_errmsg(p_sqlite));
		iRet = 3;
	}

	return iRet;
}


int main(int argc, char* argv[])
{
 	sqlite3* sqlSearch = NULL;
	sqlite3_stmt *stmt = NULL;
	int iRet = 0;
	char szSql[MAX_SQL*10] = {0};	// SQL语句
	char szScanType[MAX_SQL] = {0};
	
 	
 	if (argc<5)
 	{
		printf("Search Error:\n[-] 参数错误 \n");
 		return 1;
 	}

	if (argc>5)
	{
		if (strcmp(argv[3],"D"))
		{
			printf("Search Error:\n[-] 参数错误 \n");
			return 1;
		}		
		
	}

	strcpy(szScanType,argv[2]);

	if (strcmp(szScanType,"P10") == 0)
	{
		if (strcmp(argv[1],"1") == 0)
		{
			strcat(szScanType,"%T12");
		}
		else
		{
			strcat(szScanType,"%16*16");
		}
		
	}
	else if (strcmp(szScanType,"P3") == 0)
	{
		strcpy(szScanType,"P5");
	}
	else if (strcmp(szScanType,"P4") == 0)
	{
		strcpy(szScanType,"P5");
	}
	else if (strcmp(szScanType,"P6") == 0)
	{
		strcpy(szScanType,"P5");
	}
	
 	iRet = sqlite3_open("/var/www/xinlong/Script/LED_DB.db",&sqlSearch);
	if (iRet != SQLITE_OK)
	{
		printf("Search Error:%s\n",sqlite3_errmsg(sqlSearch));
		return 2;
	}

	

	sprintf(szSql,"select ScanWidth,ScanHeight,PortType,ScanType from (select ScanWidth,ScanHeight,PortType,ScanType from scaninfo where ScanInfo like '%s%%')",szScanType);



	// execute SQL statement
	if (sqlite3_prepare(sqlSearch, szSql, -1, &stmt, 0) == SQLITE_OK) {

		if (sqlite3_step(stmt) == SQLITE_ROW) {
			iRet = SearchVersion_Scan(argv,sqlite3_column_int(stmt,0),sqlite3_column_int(stmt,1),(const char*)sqlite3_column_text(stmt,2),sqlite3_column_int(stmt,3),sqlSearch);
		}

		sqlite3_finalize(stmt);
	} else {
		printf("Search Error:\n[-] sqlite3_prepare_v2(\"%s\") : %s\n",
			szSql, sqlite3_errmsg(sqlSearch));
		iRet = 3;
	}
	sqlite3_close(sqlSearch);

	if (g_szNetCard[0])
	{
		printf("网络卡:%s\n",g_szNetCard);
	}
	else
	{
		printf("网络卡:型号不存在\n");
	}

	if (g_szUSBCard[0])
	{
		printf("U盘卡:%s\n",g_szUSBCard);
	}
	else
	{
		printf("U盘卡:型号不存在\n");
	}

	if (g_szWifiCard[0])
	{
		printf("Wifi卡:%s\n",g_szWifiCard);
	}
	else
	{
		printf("Wifi卡:型号不存在\n");
	}


	return iRet;
}

