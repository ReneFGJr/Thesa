import { Component, OnInit, ViewChild, ElementRef, Input } from '@angular/core';
import { DataSet, Network } from 'vis-network/standalone/esm/vis-network';

@Component({
  selector: 'app-network-vis',
  templateUrl: './network-vis.component.html',
  //template: '<div #visNetwork style="width: 600px; height: 400px;"></div>',
})
export class NetworkVisComponent {
  @Input() public nodes:Array<any> | any
  @Input() public edges:Array<any> | any
  @ViewChild('visNetwork', { static: true }) container!: ElementRef;

  networkInstance!: Network;

  ngOnInit(): void {
    const nodes = new DataSet([
      { id: 1, label: 'Ciência Aberta' },
      { id: 2, label: 'Acesso Aberto' },
      { id: 3, label: 'Acceso abierto' },
      { id: 4, label: 'Ciência Cidadã' },
      { id: 5, label: 'Ciencia ciudadana' },
      { id: 6, label: 'Abertura de dados' },
      { id: 7, label: 'Apertura de datos' },
      { id: 8, label: 'Dados abertos' },
      { id: 9, label: 'Open Data' },
      { id: 10, label: 'Datos abiertos' },
      { id: 11, label: 'Dados de Pesquisa' },
      { id: 12, label: 'Research Data' },
      { id: 13, label: 'Datos de investigación' },
      { id: 14, label: 'Revisão por pares aberta' },
      { id: 15, label: 'Revisión por pares abierta' },
      { id: 16, label: 'Acesso Aberto/Open Access' },
      { id: 17, label: 'Acceso abierto/Open access' },
      { id: 18, label: 'Data journal' },
    ]);

    const edges = [
      { from: 1, to: 2, label: 'TE', font: { align: 'middle' } },
      { from: 1, to: 3, label: 'TE', font: { align: 'middle' } },
      { from: 1, to: 4, label: 'TE', font: { align: 'middle' } },
      { from: 1, to: 5, label: 'TE', font: { align: 'middle' } },
      { from: 1, to: 6, label: 'TE', font: { align: 'middle' } },
      { from: 1, to: 7, label: 'TE', font: { align: 'middle' } },
      { from: 1, to: 8, label: 'TE', font: { align: 'middle' } },
      { from: 1, to: 9, label: 'TE', font: { align: 'middle' } },
      { from: 1, to: 10, label: 'TE', font: { align: 'middle' } },
      { from: 1, to: 11, label: 'TE', font: { align: 'middle' } },
      { from: 1, to: 12, label: 'TE', font: { align: 'middle' } },
      { from: 1, to: 13, label: 'TE', font: { align: 'middle' } },
      { from: 1, to: 14, label: 'TE', font: { align: 'middle' } },
      { from: 1, to: 15, label: 'TE', font: { align: 'middle' } },
      { from: 1, to: 16, label: 'TE', font: { align: 'middle' } },
      { from: 1, to: 17, label: 'TE', font: { align: 'middle' } },
      { from: 1, to: 18, label: 'TE', font: { align: 'middle' } },
    ];

    const data = {
      nodes: nodes,
      edges: edges,
    };

    const options = {};

    this.networkInstance = new Network(
      this.container.nativeElement,
      data,
      options
    );
  }
}
