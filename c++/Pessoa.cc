#include<iostream> //biblioteca  
using namespace std;  
  
class Pessoa {  
  private:  
      float peso,altura; //variável  
      char sexo;  
      string nome;  
  public:  
  Pessoa(){ //cria o construtor objeto  
  
  }  
  void setNome(string n,float p,float a,char s){ //recebe o valor do int main  
     nome = n;  
     peso = p;  
     altura = a;  
     sexo = s;  
  }  
  
  string getNome(){  
     return nome;  
  }  
  
  void print(){ //mostra o valor na tela  
      cout << "-------------\n";  
      cout << "Nome: " << nome << endl;  
      cout << "sexo: " << sexo << endl;  
      cout << "Peso: " << peso << endl;  
      cout << "Altura: " << altura << endl;  
  }  
  
};  
  
int main(){  
   Pessoa p; // chama o construtor-padrão  
   float peso,altura;  
   char sexo;  
   string nome;  
  
   cout << "Digite o nome: ";  
   getline(cin,nome);  
   cout << "Digite o sexo: ";  
   cin >> sexo;  
   cout<< "Digite a altura: ";  
   cin >> altura;  
   cout << "Digite o peso: ";  
   cin >> peso;  
   p.setNome(nome,peso,altura,sexo); //envia o valor para void setNome  
   p.print(); //enviar o resultado para void print()  
} //fim  