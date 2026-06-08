#include <stdio.h>

int main() {
    int choice;
    float a, b;
do{

    printf("1.Addition\n2.Subtraction\n3.Multiplication\n4.Division\n");
    printf("Choose operation: ");
    scanf("%d", &choice);

    printf("Enter two numbers: ");
    scanf("%f %f", &a, &b);

    switch (choice)
	{
        case 1:
            printf("Result = %f", a + b);
            break;

        case 2:
            printf("Result = %f", a - b);
            break;

        case 3:
            printf("Result = %f", a * b);
            break;

        case 4:
            if(b != 0)
                printf("Result = %f", a / b);
            else
                printf("Division by zero!");
            break;

        default:
            printf("Invalid choice");
}}
while("calculate\n");
    return 0;
}
