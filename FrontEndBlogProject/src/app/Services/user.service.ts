import { Injectable } from '@angular/core';
import { environment } from "../_environments/ApiUrlEnvironment";
import { HttpClient, HttpHeaders } from "@angular/common/http";
import { BehaviorSubject, Observable, tap } from "rxjs";
import { User } from "../Interfaces/ReturnInterfaces";
import { TokenService } from "./token.service";

@Injectable({
    providedIn: 'root'
})
export class UserService {
    apiUlr = environment.apiUrl;
    private userSubject = new BehaviorSubject<User | null>(null);
    user$ = this.userSubject.asObservable();

    constructor(private http: HttpClient, private tokenService: TokenService) {}

    getAuthenticatedUser(token: string): Observable<{ message: string, data: User }> {
        const headers = new HttpHeaders({
            'Authorization': `Bearer ${token}`
        });
        return this.http.get<{ message: string, data: User }>(`${this.apiUlr}/auth/user`, { headers }).pipe(
            tap((response) => {
                this.userSubject.next(response.data); // Cache the user data
            })
        );
    }

    // Method to get the currently cached user without making a new request
    getCachedUser(): User | null {
        return this.userSubject.value;
    }

    // Clear cached user data when logging out or token expires
    clearUserData() {
        this.userSubject.next(null);
    }
}
