import { Injectable } from '@angular/core';
import { Observable, throwError } from 'rxjs';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { catchError, map } from 'rxjs/operators';

@Injectable({
  providedIn: 'root',
})
export class ThesaServiceService {
  http: any;
  //private url: string = 'https://cip.brapci.inf.br/api/';
  private url: string = 'http://thesa/api';

  httpOptions = {
    headers: new HttpHeaders({
      'Content-Type': 'application/json',
      Accept: 'application/json',
    }),
  };

  constructor(
    private HttpClient: HttpClient,
    private httpService: HttpClient
  ) {}

  // Language i18n
  public getIPInfo(): Observable<any> {
    return this.httpService
      .get('https://ipapi.co/json/', this.httpOptions)
      .pipe(
        map((response) => response),
        catchError(() => throwError(() => 'Problem with IP info'))
      );
  }

  public generic(type: string, dt: Array<any>) {
    let url = `${this.url}/${type}`;
    console.log(`Generic: ${url}`);
    var formData: any = new FormData();

    for (const key in dt) {
      formData.append(key, dt[key]);
    }

    return this.HttpClient.post<Array<any>>(url, formData).pipe(
      (res) => res,
      (error) => error
    );
  }

  public getId(id: number, type: string): Observable<Array<any>> {
    let url = `${this.url}/${type}/${id}`;
    console.log(`Buscador: ${url}`);
    var formData: any = new FormData();
    //formData.append('q', id);

    return this.HttpClient.post<Array<any>>(url, formData).pipe(
      (res) => res,
      (error) => error
    );
  }
}
