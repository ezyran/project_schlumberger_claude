import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { Product } from '../Models/product';

@Injectable({
  providedIn: 'root'
})
export class ProductService {

  API_GetProduct_Url: string = "https://projet-csc-backend.herokuapp.com/api/product/" as const;
  API_ListProducts_Url: string = "https://projet-csc-backend.herokuapp.com/api/product-list" as const;

  constructor(private httpClient: HttpClient) { }

  /**
   * Lecture d'un produit à pertir de son ID
   * @param pProductId ID du produit
   * @returns Un Observable<Product> du produit demandé
   */
  public GetProductFromId(pProductId: string) : Observable<Product> {
    let data = `${pProductId}`;
    return this.httpClient.get<Product>(this.API_GetProduct_Url + data);
  }

  /**
   * Lecture de tous les produits
   * @returns Un Observable<Array<Product>>
   */
  public GetAllProducts() : Observable<Array<Product>> {
    return this.httpClient.get<Array<Product>>(this.API_ListProducts_Url);
  }
}
