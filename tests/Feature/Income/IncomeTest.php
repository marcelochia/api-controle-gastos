<?php

namespace Tests\Feature\Income;

use App\Models\Income;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class IncomeTest extends TestCase
{
    use DatabaseMigrations;

    public function testRequestCanCreateAnIncome()
    {
        // Prepare
        $payload = [
            'descricao' => 'Salário',
            'valor' => 5000.22,
            'data' => '2022-08-05'
        ];

        // Act
        $response = $this->postJson('api/receitas', $payload, [
            'Authorization' => 'Bearer ' . $this->createToken()
        ]);

        // Assert
        $response->assertCreated()
                 ->assertJson(['id' => 1]);
    }

    public function testRequestCanNotCreateAnIncomeWithADuplicateDescriptionForTheSameMonth()
    {
        // Prepare
        $payloadIncome1 = [
            'descricao' => 'Salário',
            'valor' => 5000.22,
            'data' => '2022-08-05'
        ];
        $payloadIncome2 = [
            'descricao' => 'Salário',
            'valor' => 5000.22,
            'data' => '2022-08-05'
        ];

        // Act
        $this->postJson('api/receitas', $payloadIncome1, [
            'Authorization' => 'Bearer ' . $this->createToken()
        ]);
        $responseincome2 = $this->postJson('api/receitas', $payloadIncome2, [
            'Authorization' => 'Bearer ' . $this->createToken()
        ]);

        // Assert
        $responseincome2->assertStatus(409)
                 ->assertJson(['message' => "A receita '{$payloadIncome2['descricao']}' já foi informada para este mês"]);
    }

    public function testRequestCanRetrieveASpecificIncome()
    {
        // Prepare
        $income = Income::factory()->create();

        // Act
        $uri = "api/receitas/{$income->id}";
        $response = $this->getJson($uri, [
            'Authorization' => 'Bearer ' . $this->createToken()
        ]);

        // Assert
        $response->assertOk()
                 ->assertJson(['descricao' => $income->descricao]);
    }

    public function testRequestCanUpdateAnIncome()
    {
        // Prepare
        $income = Income::factory()->create();

        // Act
        $uri = "api/receitas/{$income->id}";
        $newDescription = 'Job';
        $response = $this->putJson($uri,
        [
            'descricao' => $newDescription,
            'valor' => $income->valor,
            'data' => $income->data
        ],
        [
            'Authorization' => 'Bearer ' . $this->createToken()
        ]);

        // Assert
        $response->assertOk()
                 ->assertJson(['descricao' => $newDescription]);
    }

    public function testRequestCanNotUpdateAnIncomeWithADuplicateDescriptionForTheSameMonth()
    {
        // Prepare
        $income1 = Income::factory()->create();
        $income2 = Income::factory()->create();

        // Act
        $uriIncome2 = "api/receitas/{$income2->id}";
        $response = $this->putJson($uriIncome2,
        [
            'descricao' => $income1->descricao,
            'valor' => $income2->valor,
            'data' => $income1->data
        ],
        [
            'Authorization' => 'Bearer ' . $this->createToken()
        ]);

        // Assert
        $response->assertStatus(409)
                 ->assertJson(['message' => "A receita '{$income1->descricao}' já foi informada para o mês"]);
    }

    public function testRequestCanDeleteAnIncome()
    {
        // Prepare
        $income = Income::factory()->create();

        // Act
        $uri = "api/receitas/{$income->id}";
        $response = $this->deleteJson($uri, [], [
            'Authorization' => 'Bearer ' . $this->createToken()
        ]);

        // Assert
        $response->assertNoContent()
                 ->assertDontSee($income);
    }
}
