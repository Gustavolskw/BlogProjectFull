import { Injectable } from '@angular/core';
import {HttpClient, HttpResponse} from '@angular/common/http';
import {Observable} from 'rxjs';
import {PaginatedThreads, ThreadOne} from '../Interfaces/ReturnInterfaces';
import {environment} from "../_environments/ApiUrlEnvironment";

@Injectable({
  providedIn: 'root'
})
export class ThreadsService {
  apiUlr = environment.apiUrl;
  constructor(private http: HttpClient) {
  }

  getAll(page:null|number): Observable<{ message: string, data:PaginatedThreads }> {
    return this.http.get<{ message: string, data: PaginatedThreads }>(`${this.apiUlr}/threads/list?page=${page}`);
  }

  getSingleThread(threadId:number): Observable<{message:string, data:ThreadOne}> {
    return this.http.get<{message:string, data:ThreadOne}>(`${this.apiUlr}/threads/${threadId}`);
  }



}
