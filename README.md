# ConsultorPlacas

SaaS B2B de consulta de veículos por placa, voltado para oficinas mecânicas. A oficina digita a placa do cliente e recebe dados do veículo, sugestão de óleo e outros insumos — tudo em segundos.

## Funcionalidades

- Consulta de veículo por placa (formatos antigo e Mercosul)
- Cache de 30 dias para reduzir chamadas à API externa
- Histórico de consultas por oficina
- Sugestão de óleo recomendado baseada no ano do veículo
- Planos com limite mensal de consultas (free / básico / pro)
- Autenticação com sessão PHP
- Tema claro/escuro com persistência via localStorage

## Planos

| Plano   | Consultas/mês |
|---------|--------------|
| Free    | 10           |
| Básico  | 100          |
| Pro     | Ilimitado    |

## Stack

- **Backend:** PHP puro (sem framework)
- **Banco de dados:** MySQL/MariaDB via PDO
- **Frontend:** HTML5 + CSS3 (variáveis CSS, dark mode nativo) + JavaScript vanilla
- **Servidor local:** XAMPP

## Estrutura

```
ConsultorPlacas/
├── backend/
│   ├── config/
│   │   ├── bootstrap.php      # Configuração de erros
│   │   ├── database.php       # Conexão PDO (singleton via getDB())
│   │   └── planos.php         # Limites por plano
│   ├── controllers/
│   │   ├── AuthController.php     # Login
│   │   ├── CadastroController.php # Cadastro de usuário + oficina
│   │   └── PlateController.php    # Consulta de placa (JSON)
│   ├── models/
│   │   ├── Consulta.php       # Histórico e cache de placas
│   │   └── Usuario.php        # Usuários e oficinas
│   └── services/
│       └── PlateService.php   # Validação, rate limit, chamada à API
├── database/
│   ├── schema.sql             # Schema completo do banco
│   └── seed.php               # Dados de teste
├── pages/
│   ├── login/login.php        # Tela de login
│   ├── cadastro.php           # Cadastro de nova conta
│   ├── consulta.php           # Tela principal de consulta
│   ├── historico.php          # Histórico de consultas
│   ├── logout.php
│   ├── css/
│   │   ├── login.css          # Estilos de login/cadastro
│   │   └── consulta.css       # Estilos do app logado
│   └── js/
│       └── tema.js            # Toggle de tema claro/escuro
└── README.md
```

## Banco de Dados

```sql
usuarios   (id, nome, email, senha_hash, plano, created_at)
oficinas   (id, usuario_id, nome, created_at)
consultas  (id, oficina_id, placa, resultado_json, created_at)
cache_placas (placa PK, dados_json, atualizado_em)
```

Relacionamento: `usuarios` → `oficinas` → `consultas`

## Instalação local

**Pré-requisitos:** XAMPP (Apache + MySQL)

1. Clone o repositório na pasta `htdocs`:
   ```
   d:\xampp\htdocs\projetos-estudos\ConsultaRevizzi\ConsultorPlacas
   ```

2. Crie o banco de dados e execute o schema:
   ```bash
   mysql -u root -p < database/schema.sql
   ```

3. (Opcional) Popule com dados de teste:
   ```bash
   php database/seed.php
   ```

4. Configure as credenciais do banco em `backend/config/database.php` se necessário (padrão: `root` sem senha).

5. Acesse `http://localhost/projetos-estudos/ConsultaRevizzi/ConsultorPlacas/pages/login/login.php`

## Segurança

- Senhas com `password_hash()` / `password_verify()`
- Queries com prepared statements (PDO)
- Autenticação via sessão PHP
- Pasta `database/` protegida por `.htaccess`
- Criação de usuário + oficina em transação atômica

## Status do Projeto

MVP em desenvolvimento. A integração com a API externa de placas está mockada — os dados retornados são fixos para fins de prototipagem.
