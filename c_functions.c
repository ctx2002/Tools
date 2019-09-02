#include <time.h>
#include <stdlib.h>
#include <string.h>

char* date_str(time_t *t ,char const* format)
{
	struct tm buf;
	char* dest;

	dest = (char*)malloc(128);
	if (dest == NULL) {
		return NULL;
	}

	localtime_s(&buf, t);

	strftime(dest, 128, format, &buf);
	return dest;
}

/*

time_t t = time(NULL);
char* buff = date_str(&t, "%d/%m/%Y");
puts(buff);
free(buff);

***/
