import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})

export class EmailService {
  private baseUrl = 'http://127.0.0.1:8000/emails';

  constructor(private http: HttpClient) { }

  addEmail(id: number, email: string): Observable<Object> {
    return this.http.post(`${this.baseUrl}/add/${id}`, email);
  }
}

