import { Routes } from '@angular/router';
import {HomeComponent} from './Pages/home/home.component';
import {HistoriaComponent} from './Pages/historia/historia.component';
import {LoginComponent} from './Pages/login/login.component';
import {ThreadComponent} from "./Pages/thread/thread.component";
import {ThreadsComponent} from "./Pages/threads/threads.component";
import {ThreadsAddComponent} from "./Pages/threads-add/threads-add.component";
import {adminGuardGuard} from "./Guards/admin-guard.guard";

export const routes: Routes = [
  {
    path:"home",
    component: HomeComponent,
  },
  {
    path:"historia",
    component: HistoriaComponent,
  },
  {
    path:"login",
    component: LoginComponent,
  },
  {
    path:"topicos",
    component:ThreadsComponent
  },
  {
    path:"topicos/add",
    component:ThreadsAddComponent,
    canActivate:[adminGuardGuard]
  },
  {
    path:"topicos/:id",
    component:ThreadComponent
  },
  {
    path:"",
    redirectTo:"home",
    pathMatch: "full",
  }
];
