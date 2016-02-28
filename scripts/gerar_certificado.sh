#!/bin/bash

export DIR="../arquivos/privado/certificado/sistemaweb"

#################################################
# GERAR CERTIFICADO DA AUTORIDADE CERTIFICADORA #
#################################################

# Gerar chave da Autoridade Certificadora
if [ ! -f "$DIR/ca.key" ]; then
   openssl genrsa -des3 -out $DIR/ca.key 4096
fi

# Gerar certificado X.509
if [ ! -f "$DIR/ca.crt" ]; then
   openssl req -new -x509 -days 3650 -key $DIR/ca.key -out $DIR/ca.crt
fi

################################################
#         GERAR CERTIFICADO DO SERVIDOR        #
################################################

# Gerar chave do servidor
if [ ! -f "$DIR/server.key" ]; then
   openssl genrsa -des3 -out $DIR/server.key 4096
fi

# Gerar requisição de certificado
if [ ! -f "$DIR/server.csr" ]; then
   openssl req -new -key $DIR/server.key -out $DIR/server.csr
fi

# Assinar certificado do servidor com o certificado raiz
if [ ! -f "$DIR/server.crt" ]; then
   openssl x509 -req -days 3650 -in $DIR/server.csr -CA $DIR/ca.crt -CAkey $DIR/ca.key -set_serial 10102014 -out $DIR/server.crt
fi

# Retirar senha da chave privada
if [ ! -f "$DIR/server.insecure.key" ]; then
   openssl rsa -in $DIR/server.key -out $DIR/server.insecure.key
fi

# Gerar PKCS#12
if [ ! -f "$DIR/server.pfx" ]; then
   openssl pkcs12 -export -in $DIR/server.crt -inkey $DIR/server.key -out $DIR/server.pfx 
fi
