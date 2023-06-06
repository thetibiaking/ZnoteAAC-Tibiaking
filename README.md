![image](https://github.com/thetibiaking/ttk-znote-aac/assets/74227915/6a598173-b5ed-4d5c-bdcd-164979966465)


# ZnoteAAC
[![CodeFactor](https://www.codefactor.io/repository/github/znote/znoteaac/badge)](https://www.codefactor.io/repository/github/znote/znoteaac)

## O que é o Znote AAC?
O Znote AAC é um site completo usado em conjunto com um servidor Open Tibia (OT). O objetivo é ser super fácil de instalar e compatível com todas as distribuições populares de OT. Ele foi criado em PHP com um framework procedural personalizado simples.

## Onde posso baixar?
Usamos o GitHub para distribuir nossas versões. As versões estáveis são marcadas como "releases", enquanto o desenvolvimento é o último commit.
- [Estável](https://github.com/Znote/ZnoteAAC/releases)
- [Desenvolvimento](https://github.com/Znote/ZnoteAAC/archive/master.zip)

## Requisitos
- Versão do PHP 5.6 ou superior. Principalmente testado nas versões 5.6 e 7.4. A maioria das pilhas web já inclui isso como padrão nos dias de hoje.

## Opcionais
- Para verificação de registro por e-mail e recuperação de conta: [PHPMailer](https://github.com/PHPMailer/PHPMailer/releases) Versão 6.x, extraída e renomeada para "PHPMailer" no diretório do Znote AAC.
- Extensão PHP curl para PHPMailer, PayPal e serviços reCaptcha do Google.
- Extensão PHP openssl para serviços reCaptcha do Google.
- Extensão PHP gd para logotipos de guilda.

## Instruções de instalação
1. Extraia o arquivo .zip para o diretório do seu servidor web (Exemplo: C:\UniServ\www\).
   - Sem modificar o arquivo config.php, acesse o site e aguarde o erro de conexão com o MySQL. Isso mostrará o restante das instruções, bem como o esquema do MySQL.

2. Edite o arquivo config.php e:
   - Modifique $config['ServerEngine'] com a versão correta do TFS que você está executando (TFS_02, TFS_03, TFS_10, OTHIRE).
   - Modifique $config['page_admin_access'] com o nome de usuário da sua conta de administrador.

3. Antes de inserir os detalhes corretos da conexão SQL, visite o site (http://127.0.0.1/), ele gerará um esquema do MySQL que você deve importar para o banco de dados do seu servidor OT.

4. Siga as etapas no site e importe o esquema SQL para o Znote AAC, e edite o arquivo config.php com os detalhes corretos do MySQL.

5. SE você tiver um banco de dados existente de um servidor OT ativo, acesse a pasta chamada "special" e converta o banco de dados para suporte ao Znote AAC (http://127.0.0.1/special/).

6. Aproveite o Znote AAC. Você pode conferir [AQUI](https://otland.net/forums/website-applications.118/) por plugins e recursos para o Znote AAC, como vários modelos gratuitos para usar.

7. Observe que você precisa ter o cURL ativado no PHP para que os pagamentos do PayPal funcionem.

8. Talvez seja necessário alterar as permissões de acesso ao diretório /engine/cache para permitir a gravação.

## Recursos:
O Znote AAC é rico em recursos, aqui está uma tentativa de resumir o que oferecemos.

### Compatibilidade com distribuições de servidor:
- [Znote AAC 1.6](https://github.com/Znote/ZnoteAAC/releases/tag/1.6)
  - OTHire
  - TFS 0.2
  - TFS 0.3/4
  - TFS 1.3
  - Distribuições baseadas nessas (como OTX).
- Znote AAC 2.0 [branch v2 dev](https://github.com/Znote/ZnoteAAC/tree/v2)
  - TFS 1.4
  - OTservBR-Global

### Geral
- Lista de mortes mais recentes em todo o servidor
- Lista de kills mais recentes em todo o servidor
- Informações do servidor com configurações de PvP, taxas de habilidades, estágios de experiência (analisa o arquivo config.lua e stages.xml)
- Página de magias com filtros de vocação (analisa o arquivo spells.xml)
- Lista de itens mostrando os itens equipáveis (analisa o arquivo items.xml)

### Conta e login:
- Registro básico de conta
- Alterar senha e e-mail
- Sistema antibot(spam) reCaptcha
- Verificação de e-mail e interface de recuperação de conta perdida
- Suporte para autenticação de dois fatores
- Ocultar personagens da lista de personagens
- Suporte a helpdesk (tickets)

### Criar personagem:
- Suporta vocações personalizadas, habilidades iniciais, cidades disponíveis
- Itens iniciais do personagem através de um script Lua fornecido
- Exclusão suave de personagem

### Casa:
- Lista de casas com filtro de cidades
- Licitação de casas
- Compra direta de casa com pontos da loja

### Perfil do personagem
- Informações gerais, como nome, vocação, nível, pertencimento a guilda, etc.
- Lista de conquistas obtidas
- Comentários de jogadores
- Lista de mortes
- Progressão de missões
- Lista de personagens
- Mostrador de equipamentos, habilidades, trajes completos

### Guildas
- Restrições de nível e tipo de conta configuráveis para criar guilda
- Criar e dissolver guildas
- Convidar e revogar convites de jogadores para a guilda
- Alterar nome das posições na guilda
- Adicionar apelido aos membros da guilda
- Quadro de fórum da guilda acessível apenas para membros da guilda e administrador
- Carregar imagem da guilda
- Descrição da guilda
- Convidar, aceitar e cancelar declarações de guerra
- Visualizar guerras de guildas em andamento

### Mercado de itens
- Lista de desejos de compra
- Lista de desejos de venda
- Pesquisa de itens
- Comparar oferta de item com outras ofertas semelhantes, bem como histórico de transações

### Downloads
- Página com links de download para a versão do cliente e IP changer
- Tutorial sobre como se conectar ao servidor

### Sistema de conquistas
- Lista de todas as conquistas e conquistas obtidas pelos personagens em seus perfis.

### Highscores
- Filtros de tipo de vocação e habilidade

### Compra de pontos da loja / moeda digital
- Gateway de pagamento do PayPal
- Gateway de pagamento do PayGol (SMS)
- Gateway de pagamento do PagSeguro

### Sistema de loja
- Itens
- Dias premium
- Alterar gênero do personagem
- Alterar nome do personagem
- Trajes
- Montarias
- Tipos de ofertas personalizadas (requer conhecimento básico de Lua)

### Fórum
- Criar quadros de discussão personalizados
- Restrição de nível para postar
- Avatar com visual do personagem
- Posição do jogador
- Quadros de guilda
- Quadro de feedback onde todos os tópicos são visíveis apenas para administradores
- Ocultar tópico, fechar tópico, fixar tópico
- Pesquisa no fórum

### Sistema de cache
- Reduz a carga do SQL e o uso da CPU, carregando dados tratados de um arquivo plano em vez de consultas SQL brutas.

### Administração
- Excluir personagem
- Banir personagem e/ou conta
- Alterar senha da conta
- Dar posição de jogo a um personagem
- Dar pontos da loja a um personagem
- Teleportar um jogador ou todos os jogadores para a cidade natal, cidade específica ou posição específica
- Editar nível e habilidades do jogador
- Visualizar relatórios de erros e feedback do jogo no fórum
- Visão geral das transações da loja e seus status
- Moderar imagens enviadas pelos usuários para a galeria
- Criar notícias com um editor de texto completo
- Adicionar registros de alterações (changelogs)
- Carregar e atualizar informações do servidor e magias
- Helpdesk

### Lista de tarefas:
- Verifique [Milestones](https://github.com/Znote/ZnoteAAC/milestones)
