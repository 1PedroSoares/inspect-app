<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inspection;
use Carbon\Carbon;

class InspectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * @return void
     */
    public function run(): void
    {
        $inspecao1 = Inspection::create([
            'titulo' => 'Inspeção Anual - Unidae de Saúde Canoas Canaã ',
            'cep' => '32400-000',
            'logradouro' => 'Praça',
            'numero' => '1986',
            'bairro' => 'Canaã',
            'cidade' => 'Ibirité',
            'uf' => 'MG',
            'data_prevista' => Carbon::now()->addDays(2),
            'status' => 'Pendente',
        ]);

        $inspecao1->checklistItems()->createMany([
            ['descricao' => 'Foto da fachada', 'obrigatorio' => true, 'concluido' => false],
            ['descricao' => 'Verificar extintor (validade)', 'obrigatorio' => true, 'concluido' => false],
            ['descricao' => 'Assinatura do responsável', 'obrigatorio' => true, 'concluido' => false],
            ['descricao' => 'Verificar luzes de emergência', 'obrigatorio' => false, 'concluido' => false],
            ['descricao' => 'Verificar saídas de emergência', 'obrigatorio' => false, 'concluido' => false],
        ]);

        $inspecao2 = Inspection::create([
            'titulo' => 'Inspeção de Semestral - Posto de COmbustíveis ALE',
            'cep' => '32400-000',
            'logradouro' => 'Avenida João de Deus Campos',
            'numero' => '500',
            'bairro' => 'Industrial de Ibirité',
            'cidade' => 'Ibirité',
            'uf' => 'MG',
            'data_prevista' => Carbon::now()->addDays(10),
            'status' => 'Pendente',
        ]);

        $inspecao2->checklistItems()->createMany([
            ['descricao' => 'Coletar amostra de Combustivel', 'obrigatorio' => true, 'concluido' => true],
            ['descricao' => 'Foto das bombas', 'obrigatorio' => false, 'concluido' => true],
            ['descricao' => 'Checar placas de segurança', 'obrigatorio' => true, 'concluido' => false],
            ['descricao' => 'Teste da proveta', 'obrigatorio' => true, 'concluido' => false],
            ['descricao' => 'Teste de aferição da bombaa', 'obrigatorio' => true, 'concluido' => false],
        ]);
    }
}
