# PHP-IR

**PHP-IR** is a modern, research-oriented **Information Retrieval (IR)** and **Vector Space Modeling** library for PHP, focused on correctness, transparency, and theoretical grounding.

It provides low-level, composable primitives for **text representation, weighting, similarity, clustering, and evaluation**, designed for engineers who need **full control and explainability**, not opaque ML abstractions.

---

## Why PHP-IR exists

The PHP ecosystem has historically lacked serious IR tooling beyond thin wrappers around search engines. PHP-IR fills that gap by offering:

- Explicit **vector space modeling**
- Reproducible **term weighting pipelines**
- Deterministic **clustering algorithms**
- Quantitative **cluster quality metrics**
- APIs aligned with **Information Retrieval literature**

The goal is not convenience-first APIs, but **scientifically correct and inspectable IR workflows**.

---

## Core capabilities

### Text processing
- Tokenization (regex, whitespace)
- Text normalization (lowercasing, accent folding, composition)
- Stop-word filtering with language support (English, Spanish)

### Vocabulary & statistics
- Vocabulary construction
- Document frequency tracking
- IDF computation (per-term and vectorized)
- Corpus-level statistics via dedicated façades (no core pollution)

### Vectorization
- Sparse and dense vector representations
- Term Frequency (TF)
- TF-IDF weighting
- Spherical (L2-normalized) vector spaces
- Explicit densification for algorithms that require fixed dimensions

### Similarity
- Cosine similarity
- Pluggable similarity interfaces

### Clustering
- Spherical K-Means
- **Spherical K-Medians** (robust to outliers)
- Deterministic centroid update strategies
- Explicit iteration control
- Centroid initialization and update policies

### Cluster evaluation
- Intra-cluster cohesion
- Inter-cluster separation
- Global quality score aligned with IR theory
- Metrics designed for **algorithm comparison**, not just reporting

---

## Design philosophy

PHP-IR is intentionally **not**:

- A search engine
- A machine learning framework
- A black-box clustering toolkit

Instead, it provides **clear, inspectable building blocks** that let you:

- Reason about every step of the IR pipeline
- Swap strategies without side effects
- Validate theoretical assumptions with executable code
- Compare algorithms using quantitative invariants

If you are familiar with TF-IDF, cosine similarity, and clustering theory, PHP-IR should feel predictable and rigorous.

---

## Theoretical foundation

The library is grounded in classical and modern IR research, including:

- *[Introduction to Information Retrieval](https://nlp.stanford.edu/IR-book/)* - Christopher D. Manning, Prabhakar Raghavan, Hinrich Schütze

- *[Spherical k-means clustering](http://www.cs.utexas.edu/~inderjit/public_papers/concept_mlj.pdf)* - I. S. Dhillon and D. S. Modha

- *[Spherical K - Medians](https://www.academia.edu/27956871/Spherical_K_Medians)* - Rafael E. Espinosa Santiesteban
---

## Current status

- Actively developed
- API stabilized through real-world usage
- Strong test coverage with **invariant-based tests**
- English and Spanish corpora used for validation
- Designed to evolve without breaking theoretical guarantees

> Detailed documentation, examples, and usage guides will be added incrementally.

---

## Roadmap (high level)

- Advanced convergence criteria beyond fixed iteration limits
- Additional robustness heuristics for clustering
- Optional serialization of evaluation artifacts
- Extended language tooling and corpora support

---

## License

MIT License.  
Use it, extend it, and build on it responsibly.