import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable, catchError, throwError } from 'rxjs';
import { environment } from '../../../environments/environment';

@Injectable({
  providedIn: 'root',
})
export class SocialService {
  private url: string = environment.apiUrl;

  httpOptions = {
    headers: new HttpHeaders({
      'Content-Type': 'application/json',
      Accept: 'application/json',
    }),
  };

  constructor(private httpClient: HttpClient) {}

  /**
   * Buscar perfil do usuário com seus tesauros
   * @param userId - ID do usuário
   * @returns Observable com dados do perfil
   */
  getUserProfile(userId: string | number): Observable<any> {
    const url = `${this.url}/social/profile/${userId}`;
    return this.httpClient.get<any>(url, this.httpOptions).pipe(
      catchError((error) => {
        console.error('Erro ao buscar perfil:', error);
        return throwError(() => new Error('Erro ao buscar perfil do usuário'));
      })
    );
  }

  /**
   * Atualizar dados do usuário
   * @param userId - ID do usuário
   * @param data - Dados do usuário para atualizar
   * @returns Observable com resposta da API
   */
  updateUserProfile(userId: string | number, data: any): Observable<any> {
    const url = `${this.url}/social/profile/${userId}`;
    return this.httpClient.post<any>(url, data, this.httpOptions).pipe(
      catchError((error) => {
        console.error('Erro ao atualizar perfil:', error);
        return throwError(() => new Error('Erro ao atualizar perfil do usuário'));
      })
    );
  }

  /**
   * Buscar informações de um tesauro específico
   * @param thesaId - ID do tesauro
   * @returns Observable com dados do tesauro
   */
  getThesaInfo(thesaId: string | number): Observable<any> {
    const url = `${this.url}/thesa/${thesaId}/info`;
    return this.httpClient.get<any>(url, this.httpOptions).pipe(
      catchError((error) => {
        console.error('Erro ao buscar tesauro:', error);
        return throwError(() => new Error('Erro ao buscar informações do tesauro'));
      })
    );
  }
}
