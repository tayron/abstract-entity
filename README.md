## AbstractEntity

Classe abstrata para ser extendida por entidades que venha poder expor seus atributos no 
formado de xml, string, array e json.


## Recursos
  - toXml() - Método que retorna os atributos da classe filha na estrutura de xml  
  - toJson(nomeItem) - Método que retorna os atributos da classe filha na estrutura de json
  - setAttributeWithHtml(nomeAtributo) - Método que seta nome dos atributos da classe cuja seu valor é HTML para que o método toJson o trate como um CDATA
  - toArray() - Método que retorna os atributos da classe filha na estrutura de array
  - toString() - Método que retorna os atributos da classe filha na estrutura de string
  - toSerialize() - Método que retorna a classe serializada  
    

## Utilização via composer

```sh
    "require": {
        ...
        "tayron/abstract-entity" : "1.0.0"
        ... 
    },    
```
