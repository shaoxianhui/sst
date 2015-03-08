#include <stdio.h>
#include <stdlib.h>

int main(int argc, char const* argv[])
{
    int i = 0;
    int temp = 0;
    int sum = 0;
    for(i = 1;i < argc;i++)
    {
        sscanf(argv[i], "%d", &temp);
        sum += temp;
    }
    printf("sum = %d\n", sum);
    return 0;
}
