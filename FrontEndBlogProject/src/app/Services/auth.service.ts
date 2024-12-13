import { Injectable } from '@angular/core';
import {HttpClient, HttpHeaders, HttpResponse} from '@angular/common/http';
import {Observable, tap} from 'rxjs';
import {AuthLogin} from '../Interfaces/ReturnInterfaces';
import {TokenService} from './token.service';
import {environment} from "../_environments/ApiUrlEnvironment";

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  apiUlr = environment.apiUrl;

  constructor(private http: HttpClient, private tokenService: TokenService) { }

  login(email: string, password: string): Observable<HttpResponse<AuthLogin>> {
    return this.http.post<AuthLogin>(`${this.apiUlr}/login`, {email, password},
      { observe: "response" }).pipe(
      tap((response) => {
       const authToken = response.body?.token|| null;
        if(authToken){
          this.tokenService.salvarToken(authToken);
        }
      })
    );
  }

  logout(token: string): Observable<void> {
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });
    this.tokenService.deleteToken();
    return this.http.delete<void>(`${this.apiUlr}/logout`, { headers });
  }

}
