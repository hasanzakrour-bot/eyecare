---
library_name: transformers
license: apache-2.0
base_model: google/vit-base-patch16-224
tags:
- generated_from_trainer
metrics:
- accuracy
model-index:
- name: retinal_disease_model
  results: []
---

<!-- This model card has been generated automatically according to the information the Trainer had access to. You
should probably proofread and complete it, then remove this comment. -->

# retinal_disease_model

This model is a fine-tuned version of [google/vit-base-patch16-224](https://huggingface.co/google/vit-base-patch16-224) on an unknown dataset.
It achieves the following results on the evaluation set:
- Loss: 0.8147
- Accuracy: 0.6062

## Model description

More information needed

## Intended uses & limitations

More information needed

## Training and evaluation data

More information needed

## Training procedure

### Training hyperparameters

The following hyperparameters were used during training:
- learning_rate: 2e-05
- train_batch_size: 32
- eval_batch_size: 32
- seed: 42
- optimizer: Use OptimizerNames.ADAMW_TORCH_FUSED with betas=(0.9,0.999) and epsilon=1e-08 and optimizer_args=No additional optimizer arguments
- lr_scheduler_type: cosine
- num_epochs: 10
- mixed_precision_training: Native AMP

### Training results

| Training Loss | Epoch | Step | Validation Loss | Accuracy |
|:-------------:|:-----:|:----:|:---------------:|:--------:|
| 1.5345        | 1.0   | 293  | 1.3042          | 0.3991   |
| 1.2525        | 2.0   | 586  | 1.3418          | 0.3633   |
| 1.0851        | 3.0   | 879  | 1.2063          | 0.5008   |
| 0.9413        | 4.0   | 1172 | 1.0855          | 0.4971   |
| 0.8212        | 5.0   | 1465 | 0.9673          | 0.4848   |
| 0.7118        | 6.0   | 1758 | 0.9683          | 0.5350   |
| 0.6451        | 7.0   | 2051 | 0.8728          | 0.6009   |
| 0.5910        | 8.0   | 2344 | 0.8177          | 0.5939   |
| 0.5518        | 9.0   | 2637 | 0.8230          | 0.5993   |
| 0.5383        | 10.0  | 2930 | 0.8147          | 0.6062   |


### Framework versions

- Transformers 5.0.0
- Pytorch 2.10.0+cu128
- Datasets 4.0.0
- Tokenizers 0.22.2
