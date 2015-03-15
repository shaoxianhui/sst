#! /bin/bash
gcc sum.c -o sum
gcc WebLedSearch.c sqlite3.c -lpthread -ldl -o calc
