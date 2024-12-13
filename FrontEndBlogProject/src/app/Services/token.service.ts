import { Injectable } from '@angular/core';
import { BehaviorSubject } from 'rxjs';

const KEY = "Token";

@Injectable({
  providedIn: 'root'
})
export class TokenService {
  private authStatusSubject = new BehaviorSubject<boolean>(this.istokenActive());
  authStatus$ = this.authStatusSubject.asObservable();

  salvarToken(token: string): void {
    localStorage.setItem(KEY, token);
    this.authStatusSubject.next(true); // Emite que o token está ativo
  }

  deleteToken(): void {
    localStorage.removeItem(KEY);
    this.authStatusSubject.next(false); // Emite que o token foi removido
  }

  getToken(): string {
    return localStorage.getItem(KEY) ?? "";
  }

  istokenActive(): boolean {
    return !!this.getToken();
  }
}
