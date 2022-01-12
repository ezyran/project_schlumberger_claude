import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-product-detail',
  templateUrl: './product-detail.component.html',
  styleUrls: ['./product-detail.component.css']
})
export class ProductDetailComponent implements OnInit {

  public mProductId! : string ;

  constructor(private mRoute: ActivatedRoute) { }

  ngOnInit(): void {
    let retrievedId: string | null = this.mRoute.snapshot.paramMap.get('productId');
    if (retrievedId)
      this.mProductId = retrievedId;
  }

}
