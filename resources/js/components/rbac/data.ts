import type { Module } from "./types"

export const modules: Module[] = [
  {
    id: "users",
    name: "Usuários",
    description: "Gerenciamento de usuários do sistema",
    permissions: [
      {
        id: "users.create",
        name: "Criar Usuário",
        description: "Permite criar novos usuários no sistema",
        enabled: true,
      },
      {
        id: "users.read",
        name: "Visualizar Usuários",
        description: "Permite visualizar a lista de usuários e seus detalhes",
        enabled: true,
      },
      {
        id: "users.update",
        name: "Editar Usuário",
        description: "Permite editar informações de usuários existentes",
        enabled: false,
      },
      {
        id: "users.delete",
        name: "Excluir Usuário",
        description: "Permite remover usuários do sistema",
        enabled: false,
      },
    ],
  },
  {
    id: "products",
    name: "Produtos",
    description: "Gerenciamento do catálogo de produtos",
    permissions: [
      {
        id: "products.create",
        name: "Criar Produto",
        description: "Permite adicionar novos produtos ao catálogo",
        enabled: true,
      },
      {
        id: "products.read",
        name: "Visualizar Produtos",
        description: "Permite visualizar o catálogo de produtos",
        enabled: true,
      },
      {
        id: "products.update",
        name: "Editar Produto",
        description: "Permite modificar informações dos produtos",
        enabled: true,
      },
      {
        id: "products.delete",
        name: "Excluir Produto",
        description: "Permite remover produtos do catálogo",
        enabled: false,
      },
      {
        id: "products.import",
        name: "Importar Produtos",
        description: "Permite importar produtos em lote via arquivo",
        enabled: false,
      },
    ],
  },
  {
    id: "orders",
    name: "Pedidos",
    description: "Gerenciamento de pedidos e vendas",
    permissions: [
      {
        id: "orders.create",
        name: "Criar Pedido",
        description: "Permite criar novos pedidos no sistema",
        enabled: true,
      },
      {
        id: "orders.read",
        name: "Visualizar Pedidos",
        description: "Permite visualizar pedidos e seus detalhes",
        enabled: true,
      },
      {
        id: "orders.update",
        name: "Editar Pedido",
        description: "Permite modificar pedidos existentes",
        enabled: false,
      },
      {
        id: "orders.cancel",
        name: "Cancelar Pedido",
        description: "Permite cancelar pedidos em andamento",
        enabled: false,
      },
    ],
  },
  {
    id: "reports",
    name: "Relatórios",
    description: "Acesso a relatórios e analytics",
    permissions: [
      {
        id: "reports.sales",
        name: "Relatório de Vendas",
        description: "Permite visualizar relatórios de vendas e faturamento",
        enabled: true,
      },
      {
        id: "reports.users",
        name: "Relatório de Usuários",
        description: "Permite visualizar relatórios de atividade dos usuários",
        enabled: false,
      },
      {
        id: "reports.export",
        name: "Exportar Relatórios",
        description: "Permite exportar relatórios em diferentes formatos",
        enabled: false,
      },
    ],
  },
  {
    id: "teste1",
    name: "Teste",
    description: "Acesso a relatórios e analytics",
    permissions: [],
  },
  {
    id: "teste2",
    name: "Teste 2",
    description: "Acesso a relatórios e analytics",
    permissions: [],
  },
  {
    id: "teste3",
    name: "Teste 3",
    description: "Acesso a relatórios e analytics",
    permissions: [],
  },
  {
    id: "teste4",
    name: "Teste 4",
    description: "Acesso a relatórios e analytics",
    permissions: [],
  },
  {
    id: "teste5",
    name: "Teste 5",
    description: "Acesso a relatórios e analytics",
    permissions: [],
  },
]
