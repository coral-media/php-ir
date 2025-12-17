# coral-media/php-ir

A focused PHP library implementing classical Information Retrieval algorithms
based on the Stanford IR book 
[Introduction to Information Retrieval](https://nlp.stanford.edu/IR-book/html/htmledition/irbook.html).

## Scope
- Vector space model
- Similarity measures (cosine, euclidean)
- TF / IDF / TF-IDF weighting
- Clustering (K-Means, variants)
- Deterministic, explainable algorithms

## Non-Goals
- NLP pipelines
- Transformers / embeddings
- Dataset abstractions
- Framework integrations

## Philosophy
This library favors:
- Mathematical correctness
- Explicit abstractions
- Deterministic behavior
- Native acceleration via Zephir (optional)

## License
MIT