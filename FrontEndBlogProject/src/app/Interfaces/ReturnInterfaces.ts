export interface AuthLogin {
  message: string;
  data:Array<any>;
  token: string
}

export interface User {
  id:number,
  name:string;
  email:string;
  roleName:string;
  role:number;
}

export interface PaginatedThreads {
  dados: Threads[];
  total: number
  current_page: number
  last_page: number
  per_page:number
}

export interface Threads {
  id: number;
  titulo: string;
  descricao:string;
  imagem: string;
  likes:number;
  dataCriacao:Date
  autor:string;
}

export interface ThreadOne {
  id: number;
  titulo: string;
  descricao: string;
  imagem: string;
  likes: number;
  dataCriacao: Date;
  autor: string;
  posts:Array<Post>;
  comments:Array<Comment>;
}

export interface Post{
  id: number;
  usuario:string;
  usuarioId:number;
  thread:number;
  titulo:string;
  conteudo:string;
  dataDoPost:Date;
  imagens:Array<string>;
}


export interface Comment{
  id: number,
  thread: number,
  user: number,
  comment: string,
  likes: number,
  dataComentario: Date
}

